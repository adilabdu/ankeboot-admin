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
            'user_id' => Auth::user()->id,
        ]));

        // Update the book balance
        if ($transaction['type'] === TransactionType::PURCHASE) {
            $transaction['book']->balance += $transaction['quantity'];
        } else {
            $transaction['book']->balance += ($transaction['quantity'] * -1);
        }

        $transaction['book']->save();

        // Create Consignment Record if `consignment`
        if ($transaction['book']->type === PurchaseType::CONSIGNMENT) {

            $consignmentAction = $transaction['type'] === TransactionType::PURCHASE ?
                ConsignmentAction::RECEIVED :
                ConsignmentAction::SOLD;

            $consignmentRecord = ConsignmentSettlement::create([
                'book_id' => $transaction['book']->id,
                'quantity' => $transaction['quantity'],
                'action_type' => $consignmentAction,
                'action_on' => $transaction['transaction_on']
            ]);

            // Update `payable` if SALE was made

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
//            'user_id' => 1
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
