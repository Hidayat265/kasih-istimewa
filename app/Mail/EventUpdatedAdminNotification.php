<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Event;

class EventUpdatedAdminNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $organizerName;
    public $updaterName;

    /**
     * Create a new message instance.
     */
    public function __construct(Event $event, string $organizerName, string $updaterName = null)
    {
        $this->event = $event;
        $this->organizerName = $organizerName;
        $this->updaterName = $updaterName ?? 'Organizer';
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Event Update Requires Approval - Kasih Istimewa',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.event.admin.event-updated-admin-notification',
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