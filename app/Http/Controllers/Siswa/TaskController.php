<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Task;
use App\Models\TaskSubmission;
use App\Models\TeachingAssignment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(): View
    {
        $classroom = auth()->user()->currentClassroom();
        $activeYear = AcademicYear::active();

        $tasks = collect();

        if ($classroom && $activeYear) {
            $assignmentIds = TeachingAssignment::where('classroom_id', $classroom->id)
                ->where('academic_year_id', $activeYear->id)
                ->pluck('id');

            $mySubmissions = TaskSubmission::where('student_id', auth()->id())->get()->keyBy('task_id');

            $tasks = Task::with(['teachingAssignment.subject'])
                ->whereIn('teaching_assignment_id', $assignmentIds)
                ->where('is_published', true)
                ->orderBy('deadline')
                ->get()
                ->map(function ($task) use ($mySubmissions) {
                    $task->mySubmission = $mySubmissions->get($task->id);

                    return $task;
                });
        }

        return view('siswa.tasks.index', compact('classroom', 'tasks'));
    }

    public function show(Task $task): View
    {
        $this->authorizeAccess($task);

        $submission = TaskSubmission::where('task_id', $task->id)
            ->where('student_id', auth()->id())
            ->first();

        return view('siswa.tasks.show', compact('task', 'submission'));
    }

    public function store(Request $request, Task $task): RedirectResponse
    {
        $this->authorizeAccess($task);

        $existing = TaskSubmission::where('task_id', $task->id)
            ->where('student_id', auth()->id())
            ->first();

        if ($existing?->isGraded()) {
            return back()->with('error', 'Tugas ini sudah dinilai guru, pengumpulan tidak bisa diubah lagi.');
        }

        $validated = $request->validate([
            'note' => ['nullable', 'string', 'max:2000'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,jpg,jpeg,png', 'max:10240'],
        ]);

        if (empty($validated['note']) && ! $request->hasFile('file') && ! $existing?->file_path) {
            return back()->with('error', 'Isi catatan atau lampirkan file jawaban.');
        }

        $submission = $existing ?? new TaskSubmission([
            'task_id' => $task->id,
            'student_id' => auth()->id(),
        ]);

        $submission->note = $validated['note'] ?? $submission->note;

        if ($request->hasFile('file')) {
            if ($submission->file_path) {
                Storage::disk('public')->delete($submission->file_path);
            }
            $file = $request->file('file');
            $submission->file_path = $file->store('pengumpulan-tugas/'.$task->id, 'public');
            $submission->file_original_name = $file->getClientOriginalName();
        }

        $submission->submitted_at = now();
        $submission->save();

        $message = $submission->isLate()
            ? 'Tugas berhasil dikumpulkan (terlambat dari batas waktu).'
            : 'Tugas berhasil dikumpulkan.';

        return redirect()->route('siswa.tasks.show', $task)->with('success', $message);
    }

    public function downloadAttachment(Task $task): Response
    {
        $this->authorizeAccess($task);

        abort_unless($task->hasFile(), 404);

        return Storage::disk('public')->download($task->file_path, $task->file_original_name);
    }

    public function downloadSubmission(TaskSubmission $submission): Response
    {
        abort_unless($submission->student_id === auth()->id() && $submission->file_path, 403);

        return Storage::disk('public')->download($submission->file_path, $submission->file_original_name);
    }

    private function authorizeAccess(Task $task): void
    {
        $classroom = auth()->user()->currentClassroom();

        abort_unless(
            $classroom
                && $task->teachingAssignment->classroom_id === $classroom->id
                && $task->is_published,
            403
        );
    }
}
