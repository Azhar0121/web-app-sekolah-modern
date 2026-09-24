<?php

namespace App\Http\Controllers\Ortu;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\CommunicationMessage;
use App\Models\CommunicationThread;
use App\Models\TeachingAssignment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommunicationController extends Controller
{
    public function index(Request $request): View
    {
        $parent = auth()->user();

        $threadsQuery = CommunicationThread::with(['student.studentProfile', 'teacher', 'latestMessage'])
            ->where('parent_id', $parent->id)
            ->orderByDesc('last_message_at');

        $threads = $threadsQuery->get();

        $activeThreadId = $request->query('thread');
        $activeThread   = null;

        if ($activeThreadId) {
            $activeThread = CommunicationThread::with(['student.studentProfile', 'parent', 'teacher', 'messages.sender'])
                ->where('parent_id', $parent->id)
                ->find($activeThreadId);
        }

        if (! $activeThread && $threads->isNotEmpty()) {
            $activeThread = CommunicationThread::with(['student.studentProfile', 'parent', 'teacher', 'messages.sender'])
                ->where('parent_id', $parent->id)
                ->find($threads->first()->id);
        }

        // Tandai pesan sebagai sudah dibaca jika bukan dikirim oleh ortu
        if ($activeThread) {
            CommunicationMessage::where('thread_id', $activeThread->id)
                ->where('sender_id', '!=', $parent->id)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        // Ambil daftar anak & daftar guru yang mengajar anak-anak ortu untuk modal buat pesan baru
        $children   = $parent->children()->with('studentProfile')->get();
        $activeYear = AcademicYear::active();

        // Cari guru yang mengajar atau menjadi wali kelas dari anak-anak ortu
        $teacherOptions = collect();
        foreach ($children as $child) {
            $classroom = $child->currentClassroom();
            if ($classroom) {
                // Wali kelas
                if ($classroom->homeroomTeacher) {
                    $teacherOptions->push([
                        'child_id'   => $child->id,
                        'child_name' => $child->name,
                        'teacher_id' => $classroom->homeroomTeacher->id,
                        'teacher_name' => $classroom->homeroomTeacher->name,
                        'role_label' => 'Wali Kelas ' . $classroom->name,
                    ]);
                }
                // Guru mapel
                if ($activeYear) {
                    $assignments = TeachingAssignment::with(['teacher', 'subject'])
                        ->where('classroom_id', $classroom->id)
                        ->where('academic_year_id', $activeYear->id)
                        ->get();

                    foreach ($assignments as $asg) {
                        if ($asg->teacher) {
                            $teacherOptions->push([
                                'child_id'   => $child->id,
                                'child_name' => $child->name,
                                'teacher_id' => $asg->teacher->id,
                                'teacher_name' => $asg->teacher->name,
                                'role_label' => 'Guru ' . ($asg->subject?->name ?? 'Mapel'),
                            ]);
                        }
                    }
                }
            }
        }

        $teacherOptions = $teacherOptions->unique(fn ($item) => $item['child_id'] . '-' . $item['teacher_id']);

        return view('ortu.communication.index', compact('threads', 'activeThread', 'children', 'teacherOptions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:users,id'],
            'teacher_id' => ['required', 'exists:users,id'],
            'subject'    => ['required', 'string', 'max:255'],
            'category'   => ['required', 'in:akademik,kedisiplinan,kehadiran,lainnya'],
            'message'    => ['required', 'string', 'max:5000'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx,zip', 'max:10240'],
        ]);

        // Otorisasi: pastikan student_id adalah anak ortu
        abort_unless(auth()->user()->children()->where('users.id', $validated['student_id'])->exists(), 403);

        $thread = CommunicationThread::create([
            'student_id'      => $validated['student_id'],
            'teacher_id'      => $validated['teacher_id'],
            'parent_id'       => auth()->id(),
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

        return redirect()->route('ortu.communication.index', ['thread' => $thread->id])
            ->with('success', 'Pesan konsultasi berhasil terkirim ke guru.');
    }

    public function reply(Request $request, CommunicationThread $thread): RedirectResponse
    {
        abort_unless($thread->parent_id === auth()->id(), 403);

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
}
