<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserSubscribedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $planName;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $planName)
    {
        $this->user = $user;
        $this->planName = $planName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('healthymeal.foods@gmail.com', 'Healthy Meal'),
            replyTo: [
                new Address('healthymeal.foods@gmail.com', 'Healthy Meal'),
            ],
            subject: 'Complete your subscription to start your Healthy Meal journey 🥗',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.user_subscribed',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
