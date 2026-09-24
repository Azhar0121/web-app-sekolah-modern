<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\CommunicationMessage;
use App\Models\CommunicationThread;
use App\Models\TeachingAssignment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommunicationController extends Controller
{
    public function index(Request $request): View
    {
        $teacher = auth()->user();
        $status  = $request->query('status', 'all');

        $threadsQuery = CommunicationThread::with(['student.studentProfile', 'parent', 'latestMessage'])
            ->where('teacher_id', $teacher->id)
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->orderByDesc('last_message_at');

        $threads = $threadsQuery->get();

        $activeThreadId = $request->query('thread');
        $activeThread   = null;

        if ($activeThreadId) {
            $activeThread = CommunicationThread::with(['student.studentProfile', 'parent', 'teacher', 'messages.sender'])
                ->where('teacher_id', $teacher->id)
                ->find($activeThreadId);
        }

        if (! $activeThread && $threads->isNotEmpty()) {
            $activeThread = CommunicationThread::with(['student.studentProfile', 'parent', 'teacher', 'messages.sender'])
                ->where('teacher_id', $teacher->id)
                ->find($threads->first()->id);
        }

        // Tandai pesan sebagai sudah dibaca jika bukan dikirim oleh guru
        if ($activeThread) {
            CommunicationMessage::where('thread_id', $activeThread->id)
                ->where('sender_id', '!=', $teacher->id)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        // Ambil daftar siswa yang berada di kelas diampu / wali kelas guru untuk modal buat pesan baru
        $activeYear = AcademicYear::active();

        $homeroomClassIds = Classroom::where('homeroom_teacher_id', $teacher->id)->pluck('id');
        $teachingClassIds = $activeYear
            ? TeachingAssignment::where('teacher_id', $teacher->id)
                ->where('academic_year_id', $activeYear->id)
                ->pluck('classroom_id')
            : collect();

        $classroomIds = $homeroomClassIds->merge($teachingClassIds)->unique();

        $students = User::whereHas('classroomStudents', function ($q) use ($classroomIds, $activeYear) {
            $q->whereIn('classroom_id', $classroomIds)
              ->when($activeYear, fn ($qq) => $qq->where('academic_year_id', $activeYear->id));
        })
        ->whereHas('parents') // Hanya siswa yang punya ortu tertaut
        ->with(['parents', 'studentProfile', 'classroomStudents.classroom'])
        ->orderBy('name')
        ->get();

        return view('guru.communication.index', compact('threads', 'activeThread', 'students', 'status'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:users,id'],
            'subject'    => ['required', 'string', 'max:255'],
            'category'   => ['required', 'in:akademik,kedisiplinan,kehadiran,lainnya'],
            'message'    => ['required', 'string', 'max:5000'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx,zip', 'max:10240'],
        ]);

        $student = User::with('parents')->findOrFail($validated['student_id']);
        $parent  = $student->parents->first();

        if (! $parent) {
            return back()->with('error', 'Siswa ini belum memiliki akun Orang Tua yang tertaut.');
        }

        $thread = CommunicationThread::create([
            'student_id'      => $student->id,
            'teacher_id'      => auth()->id(),
            'parent_id'       => $parent->id,
            'subject'         => $validated['subject'],
            'category'        => $validated['category'],
            'status'          => 'open',
            'last_message_at' => now(),
        ]);

        $data = [
            'thread_id' => $thread->id,
            'sender_id' => auth()->id(),
            'message'   => $validated['message'],
            'is_read'   => true,
        ];

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $data['attachment_path'] = $file->store('komunikasi', 'public');
            $data['attachment_name'] = $file->getClientOriginalName();
        }

        CommunicationMessage::create($data);

        return redirect()->route('guru.communication.index', ['thread' => $thread->id])
            ->with('success', 'Ruang komunikasi berhasil dibuat dan pesan pertama telah terkirim.');
    }

    public function reply(Request $request, CommunicationThread $thread): RedirectResponse
    {
        abort_unless($thread->teacher_id === auth()->id(), 403);

        $validated = $request->validate([
            'message'    => ['required', 'string', 'max:5000'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx,zip', 'max:10240'],
        ]);

        $data = [
            'thread_id' => $thread->id,
            'sender_id' => auth()->id(),
            'message'   => $validated['message'],
            'is_read'   => true,
        ];

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $data['attachment_path'] = $file->store('komunikasi', 'public');
            $data['attachment_name'] = $file->getClientOriginalName();
        }

        CommunicationMessage::create($data);

        $thread->update([
            'last_message_at' => now(),
            'status'          => 'open',
        ]);

        return back()->with('success', 'Pesan berhasil dikirim.');
    }

    public function toggleStatus(CommunicationThread $thread): RedirectResponse
    {
        abort_unless($thread->teacher_id === auth()->id(), 403);

        $newStatus = $thread->status === 'open' ? 'resolved' : 'open';
        $thread->update(['status' => $newStatus]);

        $statusText = $newStatus === 'resolved' ? 'ditandai Selesai' : 'dibuka kembali';

        return back()->with('success', "Ruang komunikasi berhasil {$statusText}.");
    }
}
