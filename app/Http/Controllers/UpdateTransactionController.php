<?php

namespace App\Http\Controllers;

use App\Enums\TransactionType;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UpdateTransactionController extends Controller
{
    public function index(Transaction $transaction, Request $request)
    {

        /**
         * Possible updatable fields:
         * - invoice
         * - transaction_on
         * - quantity
         */

        $validator = Validator::make($request->all(), [
            'invoice' => 'required',
            'transaction_on' => 'required|date_format:d-m-Y',
            'quantity' => 'required',
            'quantity.*' => Rule::requiredIf($transaction->type === TransactionType::PURCHASE),
            'quantity.*.store_id' => [
                Rule::requiredIf($transaction->type === TransactionType::PURCHASE),
                'exists:stores,id',
            ],
            'quantity.*.amount' => [
                Rule::requiredIf($transaction->type === TransactionType::PURCHASE),
                'integer',
            ],
        ]);

        $validator->sometimes('quantity', 'array', function ($input) use ($transaction) {
            return $transaction->type === TransactionType::PURCHASE;
        });

        $validator->sometimes('quantity', 'integer', function ($input) use ($transaction) {
            return $transaction->type === TransactionType::SALE;
        });

        $validator->validate();

        if ($transaction->type === TransactionType::PURCHASE) {
            $this->updatePurchase();
        } else {
            $this->updateSale();
        }
    }

    private function updatePurchase()
    {
        dd('Updating Purchase');
    }

    private function updateSale()
    {
        dd('Updating Sale');
    }
}
