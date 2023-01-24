<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class QueueJobFinished extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(array $job)
    {
        $this->job = $job;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via(mixed $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray(mixed $notifiable): array
    {
        return $this->job;
    }

    public function toTelegram($notifiable): TelegramMessage
    {
        return TelegramMessage::create()
            ->to($notifiable->telegram_chat_id)
            ->content('Job finished');
    }
}
