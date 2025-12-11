<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GuestApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $guest)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Guest Account Has Been Approved',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.guest-approved',
            with: [
                'guest' => $this->guest,
            ],
        );
    }
}