<?php

namespace App\Notifications;

use App\Models\TaskSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TaskSubmittedNotification extends Notification
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
        $studentName = $this->submission->student?->name ?? 'Siswa';
        $task = $this->submission->task;
        $taskTitle = $task?->title ?? 'Tugas';
        $subject = $task?->teachingAssignment?->subject?->name ?? '';

        $url = $task
            ? route('guru.teaching-assignments.tasks.submissions', [$task->teaching_assignment_id, $task->id], false)
            : route('guru.dashboard', [], false);

        return [
            'title'   => 'Pengumpulan Tugas Baru',
            'message' => "{$studentName} telah mengumpulkan tugas: {$taskTitle}" . ($subject ? " ({$subject})" : "") . ".",
            'url'     => $url,
            'icon'    => 'bi-file-earmark-check-fill',
        ];
    }
}
