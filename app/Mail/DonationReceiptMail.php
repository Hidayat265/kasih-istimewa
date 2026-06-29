<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Donation;
use App\Models\User;

class DonationReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public $donation;
    public $user;

    public function __construct(Donation $donation, $user = null)
    {
        $this->donation = $donation;
        $this->user = $user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Donation Receipt - Kasih Istimewa',
        );
    }

    public function content(): Content
    {
        // Use the correct view path
        return new Content(
            view: 'emails.donation-receipt',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}