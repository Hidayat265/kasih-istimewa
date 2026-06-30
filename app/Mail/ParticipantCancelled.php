<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Event;
use App\Models\User;

class ParticipantCancelled extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $participant;
    public $reason;
    public $cancelledBy;

    public function __construct(Event $event, User $participant, $reason = null, $cancelledBy = null)
    {
        $this->event = $event;
        $this->participant = $participant;
        $this->reason = $reason;
        $this->cancelledBy = $cancelledBy;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Registration Cancelled - ' . $this->event->event_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.event.user.participant-cancelled',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}