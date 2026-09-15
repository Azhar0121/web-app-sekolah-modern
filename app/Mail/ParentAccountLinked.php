<?php

namespace App\Mail;

use App\Models\PpdbRegistration;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ParentAccountLinked extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public PpdbRegistration $registration,
        public User $parentUser,
        public ?string $password,
        public bool $isNewAccount,
    ) {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->isNewAccount
                ? 'Akun Portal Orang Tua Anda Sudah Aktif - ' . config('app.name')
                : 'Anak Baru Ditautkan ke Akun Portal Orang Tua Anda - ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ppdb.parent-account-linked',
        );
    }
}
