<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GuestRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $guest)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Guest Account Registration - Pending Approval',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.guest-registration',
            with: [
                'guest' => $this->guest,
            ],
        );
    }
}