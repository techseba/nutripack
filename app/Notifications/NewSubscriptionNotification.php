<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewSubscriptionNotification extends Notification
{
    use Queueable;

    public $subscriber;
    public $subscribedAt;

    public function __construct($subscriber, $subscribedAt = null)
    {
        $this->subscriber = $subscriber;
        $this->subscribedAt = $subscribedAt ? Carbon::parse($subscribedAt) : Carbon::now();
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $time = $this->subscribedAt->setTimezone(config('app.timezone'))->format('d M Y H:i');

        return (new MailMessage)
            ->subject('New subscription — ' . $this->subscriber->user->name)
            ->greeting('Hello Admin,')
            ->line($this->subscriber->user->name . ' subscribed at ' . $time)
            ->line('Plan: ' . ($this->subscriber->plan->name ?? 'N/A'))
            ->action('View in Admin', url('/admin/subscribers'))
            ->line('Contact: ' . ($this->subscriber->user->phone ?? 'N/A'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'subscriber_id' => $this->subscriber->id,
            'name' => $this->subscriber->user->name,
            'email' => $this->subscriber->user->email,
            'phone' => $this->subscriber->user->phone,
            'plan' => $this->subscriber->plan_name ?? null,
            'subscribed_at' => $this->subscribedAt->toDateTimeString(),
        ];
    }
}
