<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\ClassroomStudent;
use App\Models\Task;
use App\Models\TaskSubmission;
use App\Models\TeachingAssignment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(TeachingAssignment $teachingAssignment): View
    {
        $this->authorizeAssignment($teachingAssignment);

        $tasks = $teachingAssignment->tasks()
            ->withCount('submissions')
            ->latest()
            ->get();

        return view('guru.tasks.index', compact('teachingAssignment', 'tasks'));
    }

    public function create(TeachingAssignment $teachingAssignment): View
    {
        $this->authorizeAssignment($teachingAssignment);

        return view('guru.tasks.create', compact('teachingAssignment'));
    }

    public function store(Request $request, TeachingAssignment $teachingAssignment): RedirectResponse
    {
        $this->authorizeAssignment($teachingAssignment);

        $validated = $this->validateTask($request);

        $task = new Task($validated);
        $task->teaching_assignment_id = $teachingAssignment->id;
        $task->is_published = $request->boolean('is_published', true);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $task->file_path = $file->store('tugas/'.$teachingAssignment->id, 'public');
            $task->file_original_name = $file->getClientOriginalName();
        }

        $task->save();

        return redirect()
            ->route('guru.teaching-assignments.tasks.index', $teachingAssignment)
            ->with('success', 'Tugas berhasil dibuat.');
    }

    public function edit(TeachingAssignment $teachingAssignment, Task $task): View
    {
        $this->authorizeAssignment($teachingAssignment);
        $this->authorizeTask($teachingAssignment, $task);

        return view('guru.tasks.edit', compact('teachingAssignment', 'task'));
    }

    public function update(Request $request, TeachingAssignment $teachingAssignment, Task $task): RedirectResponse
    {
        $this->authorizeAssignment($teachingAssignment);
        $this->authorizeTask($teachingAssignment, $task);

        $validated = $this->validateTask($request);

        $task->fill($validated);
        $task->is_published = $request->boolean('is_published', true);

        if ($request->hasFile('file')) {
            if ($task->file_path) {
                Storage::disk('public')->delete($task->file_path);
            }
            $file = $request->file('file');
            $task->file_path = $file->store('tugas/'.$teachingAssignment->id, 'public');
            $task->file_original_name = $file->getClientOriginalName();
        } elseif ($request->boolean('remove_file')) {
            if ($task->file_path) {
                Storage::disk('public')->delete($task->file_path);
            }
            $task->file_path = null;
            $task->file_original_name = null;
        }

        $task->save();

        return redirect()
            ->route('guru.teaching-assignments.tasks.index', $teachingAssignment)
            ->with('success', 'Tugas berhasil diperbarui.');
    }

    public function destroy(TeachingAssignment $teachingAssignment, Task $task): RedirectResponse
    {
        $this->authorizeAssignment($teachingAssignment);
        $this->authorizeTask($teachingAssignment, $task);

        if ($task->file_path) {
            Storage::disk('public')->delete($task->file_path);
        }
        foreach ($task->submissions as $submission) {
            if ($submission->file_path) {
                Storage::disk('public')->delete($submission->file_path);
            }
        }
        $task->delete();

        return redirect()
            ->route('guru.teaching-assignments.tasks.index', $teachingAssignment)
            ->with('success', 'Tugas berhasil dihapus.');
    }

    /** Daftar seluruh siswa di kelas ini + status pengumpulan tugasnya */
    public function submissions(TeachingAssignment $teachingAssignment, Task $task): View
    {
        $this->authorizeAssignment($teachingAssignment);
        $this->authorizeTask($teachingAssignment, $task);

        $studentIds = ClassroomStudent::where('academic_year_id', $teachingAssignment->academic_year_id)
            ->where('classroom_id', $teachingAssignment->classroom_id)
            ->with('student')
            ->get()
            ->pluck('student');

        $submissionsByStudent = $task->submissions()->get()->keyBy('student_id');

        return view('guru.tasks.submissions', compact('teachingAssignment', 'task', 'studentIds', 'submissionsByStudent'));
    }

    public function grade(Request $request, TeachingAssignment $teachingAssignment, Task $task, TaskSubmission $submission): RedirectResponse
    {
        $this->authorizeAssignment($teachingAssignment);
        $this->authorizeTask($teachingAssignment, $task);
        abort_unless($submission->task_id === $task->id, 404);

        $validated = $request->validate([
            'grade' => ['required', 'integer', 'min:0', 'max:100'],
            'feedback' => ['nullable', 'string', 'max:2000'],
        ]);

        $submission->update([
            'grade' => $validated['grade'],
            'feedback' => $validated['feedback'] ?? null,
            'graded_at' => now(),
        ]);

        return back()->with('success', "Nilai untuk {$submission->student->name} berhasil disimpan.");
    }

    private function authorizeAssignment(TeachingAssignment $teachingAssignment): void
    {
        abort_unless($teachingAssignment->teacher_id === auth()->id(), 403, 'Anda tidak mengampu kelas/mapel ini.');
    }

    private function authorizeTask(TeachingAssignment $teachingAssignment, Task $task): void
    {
        abort_unless($task->teaching_assignment_id === $teachingAssignment->id, 404);
    }

    private function validateTask(Request $request): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'deadline' => ['required', 'date'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip', 'max:10240'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        return $validated;
    }
}