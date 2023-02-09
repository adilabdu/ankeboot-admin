<?php

namespace App\Listeners;

use App\Events\ReminderReached;
use App\Notifications\NewReminder;
use Illuminate\Support\Facades\Notification;

class SendNewReminderNotifications
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ReminderReached  $event
     * @return void
     */
    public function handle(ReminderReached $event): void
    {
        Notification::send($event->reminder->user, new NewReminder($event->reminder));
    }
}
