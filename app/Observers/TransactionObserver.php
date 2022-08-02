<?php

namespace App\Observers;

use App\Enums\ActivityType;
use App\Models\Log;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionObserver
{
    /**
     * Handle the Transaction "created" event.
     *
     * @param Transaction $transaction
     * @return void
     */
    public function created(Transaction $transaction): void
    {
        $transaction->logs()->save(new Log([
            'type' => ActivityType::CREATE,
            'user_id' => Auth::user()->id
        ]));
    }

    /**
     * Handle the Transaction "updated" event.
     *
     * @param Transaction $transaction
     * @return void
     */
    public function updated(Transaction $transaction): void
    {
        $transaction->logs()->save(new Log([
            'type' => ActivityType::UPDATE,
            'user_id' => Auth::user()->id
        ]));
    }

    /**
     * Handle the Transaction "deleted" event.
     *
     * @param Transaction $transaction
     * @return void
     */
    public function deleted(Transaction $transaction): void
    {
        $transaction->logs()->save(new Log([
            'type' => ActivityType::DELETE,
            'user_id' => Auth::user()->id
        ]));
    }

    /**
     * Handle the Transaction "restored" event.
     *
     * @param Transaction $transaction
     * @return void
     */
    public function restored(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "force deleted" event.
     *
     * @param Transaction $transaction
     * @return void
     */
    public function forceDeleted(Transaction $transaction): void
    {
        //
    }
}
