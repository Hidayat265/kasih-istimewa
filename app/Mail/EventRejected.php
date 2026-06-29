<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Event;

class EventRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $userName;
    public $reason;

    public function __construct(Event $event, $userName, $reason)
    {
        $this->event = $event;
        $this->userName = $userName;
        $this->reason = $reason;
    }

    public function build()
    {
        return $this->subject('Event Rejected - Kasih Istimewa')
                    ->view('emails.event.user.event-rejected');
    }
}