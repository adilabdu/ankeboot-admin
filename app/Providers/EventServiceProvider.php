<?php

namespace App\Providers;

use App\Events\ReminderReached;
use App\Events\SubscriberRegistered;
use App\Listeners\SendNewReminderNotifications;
use App\Listeners\SendNewSubscriberNotifications;
use App\Models\Book;
use App\Models\StoreBook;
use App\Models\Transaction;
use App\Observers\BookObserver;
use App\Observers\StoreBookObserver;
use App\Observers\TransactionObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        SubscriberRegistered::class => [
            SendNewSubscriberNotifications::class,
        ],

        ReminderReached::class => [
            SendNewReminderNotifications::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot(): void
    {
        Book::observe(BookObserver::class);
        Transaction::observe(TransactionObserver::class);
        StoreBook::observe(StoreBookObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
