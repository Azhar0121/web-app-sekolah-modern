<?php

namespace App\Mail;

use App\Models\Classroom;
use App\Models\PpdbRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudentAccountCreated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public PpdbRegistration $registration,
        public string $email,
        public string $password,
        public ?Classroom $classroom,
    ) {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Akun Siswa Anda Sudah Aktif - ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ppdb.account-created',
        );
    }
}
