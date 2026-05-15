<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriberPaymentSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subscriber;
    public $firstDeliveryDate;

    /**
     * Create a new message instance.
     */
    public function __construct($subscriber, $firstDeliveryDate)
    {
        $this->subscriber = $subscriber;
        $this->firstDeliveryDate = $firstDeliveryDate;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Confirmed — Healthy Meal',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.subscriber_payment_success',
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
