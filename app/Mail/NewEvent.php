<?php

namespace App\Mail;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewEvent extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $creatorName;

    /**
     * Create a new message instance.
     */
    public function __construct(Event $event, $creatorName)
    {
        $this->event = $event;
        $this->creatorName = $creatorName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'NEW: Event Pending Approval - ' . $this->event->event_name . ' | Kasih Istimewa',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.event.admin.new-event',
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