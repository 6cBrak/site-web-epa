<?php

namespace App\Mail;

use App\Models\Candidature;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NouvelleCandidature extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Candidature $candidature)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nouvelle candidature — '.$this->candidature->formation->title_fr,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.nouvelle-candidature',
        );
    }
}
