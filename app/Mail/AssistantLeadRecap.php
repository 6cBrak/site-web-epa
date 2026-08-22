<?php

namespace App\Mail;

use App\Models\AssistantLeadCapture;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AssistantLeadRecap extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public AssistantLeadCapture $lead)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'EPA_BURKINA — On a bien noté votre intérêt !',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.assistant-lead-recap',
        );
    }
}
