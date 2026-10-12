<?php

namespace App\Mail;

use App\Models\EvenementInscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EvenementInscriptionConfirmee extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public EvenementInscription $inscription)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'EPA — Inscription confirmée : '.$this->inscription->evenement->title_fr,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.evenement-inscription-confirmee',
        );
    }
}
