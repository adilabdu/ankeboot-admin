<?php

namespace App\Observers;

use App\Enums\ActivityType;
use App\Enums\ConsignmentAction;
use App\Enums\PurchaseType;
use App\Enums\TransactionType;
use App\Models\ConsignmentSettlement;
use App\Models\Log;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log as LaravelLog;

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
        if (Auth::user()) {

            $transaction->logs()->save(new Log([
                'type' => ActivityType::CREATE,
                'user_id' => Auth::user()->id,
            ]));

        } else {

            LaravelLog::info('Transaction created from an unauthorized / unauthenticated origin');

        }
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
            'user_id' => Auth::user()->id,
            // 'user_id' => 1,
        ]));

        LaravelLog::info($transaction->getOriginal()['quantity']);

        // Syncing book balance
        if (
            $transaction->getOriginal()['type'] !== $transaction->type ||
            $transaction->getOriginal()['quantity'] !== $transaction->quantity
        ) {

            // Reset book balance
            if ($transaction->getOriginal()['type'] === TransactionType::SALE) {
                $transaction['book']->balance += $transaction->getOriginal()['quantity'];
            } else {
                $transaction['book']->balance += ($transaction->getOriginal()['quantity'] * -1);
            }

            // Update the book balance
            if ($transaction['type'] === TransactionType::PURCHASE) {
                $transaction['book']->balance += $transaction['quantity'];
            } else {
                $transaction['book']->balance += ($transaction['quantity'] * -1);
            }

            // Commit
            $transaction['book']->save();
        }

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
