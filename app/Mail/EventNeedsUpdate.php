<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Event;

class EventNeedsUpdate extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $userName;
    public $feedback;

    public function __construct(Event $event, $userName, $feedback)
    {
        $this->event = $event;
        $this->userName = $userName;
        $this->feedback = $feedback;
    }

    public function build()
    {
        return $this->subject('Event Needs Update - Kasih Istimewa')
                    ->view('emails.event.user.event-needs-update');
    }
}