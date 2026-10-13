<?php

namespace App\Notifications;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ContactMessageReceivedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public ContactMessage $contactMessage
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $subjectPreview = \Illuminate\Support\Str::limit($this->contactMessage->subject, 40);
        $name = $this->contactMessage->name;

        return [
            'title'   => 'Pesan Masuk Baru dari Publik',
            'message' => "Pesan baru dari {$name} terkait: \"{$subjectPreview}\"",
            'url'     => route('admin.contact-messages.show', $this->contactMessage->id, false),
            'icon'    => 'bi-chat-left-text-fill',
        ];
    }
}
