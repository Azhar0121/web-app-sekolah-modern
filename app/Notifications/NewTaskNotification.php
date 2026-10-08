<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewTaskNotification extends Notification
{
    use Queueable;

    public function __construct(public Task $task)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $subject = $this->task->teachingAssignment?->subject?->name ?? 'Mata Pelajaran';
        $teacher = $this->task->teachingAssignment?->teacher?->name ?? 'Guru';
        $deadline = $this->task->due_date ? \Carbon\Carbon::parse($this->task->due_date)->translatedFormat('d M Y, H:i') : 'Tanpa batas waktu';

        return [
            'title'   => "Tugas Baru: {$this->task->title}",
            'message' => "Guru {$teacher} ({$subject}) memberikan tugas baru. Batas pengumpulan: {$deadline}.",
            'url'     => route('siswa.tasks.show', $this->task->id),
            'icon'    => 'bi-journal-check',
        ];
    }
}
