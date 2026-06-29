<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Event;

class EventUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $userName;
    public $isAdminUpdate;
    public $updaterName;

    /**
     * Create a new message instance.
     */
    public function __construct(Event $event, string $userName, bool $isAdminUpdate = false, string $updaterName = null)
    {
        $this->event = $event;
        $this->userName = $userName;
        $this->isAdminUpdate = $isAdminUpdate;
        $this->updaterName = $updaterName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        if ($this->isAdminUpdate) {
            $subject = 'Your Event Has Been Updated by Admin - Kasih Istimewa';
        } else {
            $subject = 'Event Update Submitted for Approval - Kasih Istimewa';
        }
        
        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.event.user.event-updated',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}