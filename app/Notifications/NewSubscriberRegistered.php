<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;
use App\Models\MailingList;

class NewSubscriberRegistered extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(MailingList $mailingList)
    {
        $this->mailingList = $mailingList;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'telegram'];
    }

    // /**
    //  * Get the mail representation of the notification.
    //  *
    //  * @param  mixed  $notifiable
    //  * @return \Illuminate\Notifications\Messages\MailMessage
    //  */
    // public function toMail($notifiable)
    // {
    //     return (new MailMessage)
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'mailing_list_id' => $this->mailingList->id,
            'name' => $this->mailingList->name,
            'phone' => $this->mailingList->phone,
            'email' => $this->mailingList->email,
        ];
    }

    public function toTelegram($notifiable)
    {
        return TelegramMessage::create()
                ->to($notifiable->telegram_chat_id)
                ->content(
                    'New subscriber registered! *' . 
                    $this->mailingList->name . 
                    '* has joined the mailing list on ' . 
                    $this->mailingList->created_at->format('d/m/Y H:i:s') . 
                    '.'
                );
    }
}
