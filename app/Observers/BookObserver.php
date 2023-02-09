<?php

namespace App\Observers;

use App\Enums\ActivityType;
use App\Models\Book;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log as LaravelLog;

class BookObserver
{
    public bool $afterCommit = true;

    /**
     * Handle the Book "created" event.
     *
     * @param  Book  $book
     * @return void
     */
    public function created(Book $book): void
    {
        if (Auth::user()) {
            $book->logs()->save(new Log([
                'type' => ActivityType::CREATE,
                'user_id' => Auth::user()->id,
            ]));
        } else {
            LaravelLog::info('Book created from an unauthorized / unauthenticated origin');
        }
    }

    /**
     * Handle the Book "updated" event.
     *
     * @param  Book  $book
     * @return void
     */
    public function updated(Book $book): void
    {
        $book->logs()->save(new Log([
            'type' => ActivityType::UPDATE,
            'user_id' => Auth::user()->id,
            //            'user_id' => 1
        ]));
    }

    /**
     * Handle the Book "deleted" event.
     *
     * @param  Book  $book
     * @return void
     */
    public function deleted(Book $book): void
    {
        $book->logs()->save(new Log([
            'type' => ActivityType::DELETE,
            'user_id' => Auth::user()->id,
        ]));
    }

    /**
     * Handle the Book "restored" event.
     *
     * @param  Book  $book
     * @return void
     */
    public function restored(Book $book): void
    {
        //
    }

    /**
     * Handle the Book "force deleted" event.
     *
     * @param  Book  $book
     * @return void
     */
    public function forceDeleted(Book $book): void
    {
        //
    }
}
