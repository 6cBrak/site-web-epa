<?php

namespace App\Mail;

use App\Models\Candidature;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CandidatureConfirmee extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Candidature $candidature)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'EPA — Votre candidature a bien été reçue',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.candidature-confirmee',
        );
    }
}
