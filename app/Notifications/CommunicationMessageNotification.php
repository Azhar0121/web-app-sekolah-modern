<?php

namespace App\Notifications;

use App\Models\CommunicationThread;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CommunicationMessageNotification extends Notification
{
    use Queueable;

    public function __construct(
        public CommunicationThread $thread,
        public User $sender,
        public string $targetRole = 'ortu' // 'ortu' | 'guru'
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $subject = $this->thread->subject;
        $senderName = $this->sender->name;
        $studentName = $this->thread->student?->name ?? 'Siswa';

        if ($this->targetRole === 'ortu') {
            $url = route('ortu.communication.index', ['thread' => $this->thread->id], false);
            $title = "Pesan Baru dari Guru ({$senderName})";
            $message = "Guru {$senderName} mengirim pesan mengenai: {$subject}.";
        } else {
            $url = route('guru.communication.show', $this->thread->id, false);
            $title = "Pesan Baru dari Orang Tua ({$senderName})";
            $message = "Orang tua {$studentName} mengirim pesan mengenai: {$subject}.";
        }

        return [
            'title'   => $title,
            'message' => $message,
            'url'     => $url,
            'icon'    => 'bi-chat-dots-fill',
        ];
    }
}
