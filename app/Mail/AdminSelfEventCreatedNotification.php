<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Event;

class AdminSelfEventCreatedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $adminName;

    /**
     * Create a new message instance.
     */
    public function __construct(Event $event, string $adminName)
    {
        $this->event = $event;
        $this->adminName = $adminName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Event Created by Admin - Kasih Istimewa',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.event.admin.admin-self-event-created-notification', // Updated path
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