<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GuestRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $guest)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Guest Account Status Update - CPAC ESFMS',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.guest-rejected',
            with: [
                'guest' => $this->guest,
            ],
        );
    }
}