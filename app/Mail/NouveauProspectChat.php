<?php

namespace App\Mail;

use App\Models\AssistantLeadCapture;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NouveauProspectChat extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public AssistantLeadCapture $lead)
    {
    }

    public function envelope(): Envelope
    {
        $prefix = $this->lead->priority === 'chaud' ? '🔥 URGENT — ' : '';

        return new Envelope(
            subject: $prefix.'Nouveau prospect via le chat — '.$this->lead->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.nouveau-prospect-chat',
        );
    }
}
