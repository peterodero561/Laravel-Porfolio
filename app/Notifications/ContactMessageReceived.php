<?php

namespace App\Notifications;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactMessageReceived extends Notification
{
    use Queueable;

    public function __construct(public readonly ContactMessage $message) {}

    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New contact: '.$this->message->subject)
            ->greeting('New message from '.$this->message->name)
            ->line('Email: '.$this->message->email)
            ->line('Subject: '.$this->message->subject)
            ->line('—')
            ->line($this->message->message)
            ->action('View in admin', route('admin.messages'));
    }
}
