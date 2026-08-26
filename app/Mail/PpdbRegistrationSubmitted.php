<?php

namespace App\Mail;

use App\Models\PpdbRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PpdbRegistrationSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public PpdbRegistration $registration)
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nomor Pendaftaran PPDB Anda: ' . $this->registration->registration_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ppdb.registration-submitted',
        );
    }
}
