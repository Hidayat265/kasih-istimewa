<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Event;

class EventPublished extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $userName;

    public function __construct(Event $event, $userName)
    {
        $this->event = $event;
        $this->userName = $userName;
    }

    public function build()
    {
        return $this->subject('Event Published - Kasih Istimewa')
                    ->view('emails.event.user.event-published');
    }
}