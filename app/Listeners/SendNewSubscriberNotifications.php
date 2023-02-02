<?php

namespace App\Listeners;

use App\Events\SubscriberRegistered;
use App\Models\User;
use App\Notifications\NewSubscriber;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendNewSubscriberNotifications implements ShouldQueue
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
     * @param SubscriberRegistered $event
     * @return void
     */
    public function handle(SubscriberRegistered $event): void
    {
        Notification::send(User::all(), new NewSubscriber($event->mailingList));
    }
}
