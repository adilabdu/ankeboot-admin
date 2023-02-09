<?php

namespace App\Notifications;

use App\Models\MailingList;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class NewSubscriber extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(public MailingList $mailingList)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via(mixed $notifiable): array
    {
        return ['database', 'telegram'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail(mixed $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray(mixed $notifiable): array
    {
        return [
            'name' => $this->mailingList->name,
            'email' => $this->mailingList->email,
            'phone' => $this->mailingList->phone,
            'created_at' => $this->mailingList->created_at,
        ];
    }

    /**
     * Get the Telegram representation of the notification.
     *
     * @param $notifiable
     * @return TelegramMessage
     */
    public function toTelegram($notifiable): TelegramMessage
    {
        return TelegramMessage::create()
            ->to($notifiable->telegram_chat_id)
            ->view('telegram.subscriber', [
                'name' => $this->mailingList->name,
                'created_at' => $this->mailingList->created_at->format('d/m/Y H:i:s'),
            ]);
    }
}
