<?php

namespace App\Notifications;

use App\Models\TaskSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TaskGradedNotification extends Notification
{
    use Queueable;

    public function __construct(public TaskSubmission $submission)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $task = $this->submission->task;
        $subject = $task?->teachingAssignment?->subject?->name ?? 'Mata Pelajaran';
        $score = $this->submission->grade ?? '-';
        $taskTitle = $task?->title ?? 'Tugas';

        return [
            'title'   => "Tugas Dinilai: {$taskTitle}",
            'message' => "Tugas {$taskTitle} ({$subject}) telah dinilai dengan skor {$score}/100.",
            'url'     => $task ? route('siswa.tasks.show', $task->id) : route('siswa.dashboard'),
            'icon'    => 'bi-award-fill',
        ];
    }
}
