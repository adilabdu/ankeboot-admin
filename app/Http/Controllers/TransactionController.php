<?php

namespace App\Http\Controllers;

use App\Enums\TransactionType;
use App\Models\Store;
use App\Models\StoreBook;
use App\Models\StoreTransaction;
use App\Models\Transaction;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;

class TransactionController extends Controller
{
    public function index(Request $request): Response|Application|ResponseFactory
    {
        $request->validate([
            'id' => 'integer|exists:transactions',
        ]);

        try {
            if ($request->has('id')) {
                $books = [
                    'transaction' => Transaction::with('book')
                        ->find($request->input('id')),
                    'stores' => StoreTransaction::with('store')
                        ->where('transaction_id', $request->input('id'))
                        ->get(),
                ];
            } else {
                $books = Transaction::with('book')->get();
            }

            return response([
                'message' => 'ok',
                'data' => $books,
            ], 200);
        } catch (Exception $e) {
            return response([
                'message' => 'error',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    public function post(Request $request): Response|Application|ResponseFactory
    {
        $validator = Validator::make($request->all(), [
            'invoice' => 'required',
            'book_id' => 'required|exists:books,id',
            'transaction_on' => 'required|date_format:d-m-Y',
            'type' => [
                'required',
                new Enum(TransactionType::class),
            ],
            'quantity' => 'required',
            'quantity.*' => [
                'required_if:type,purchase',
            ],
            'quantity.*.store_id' => 'required_if:type,purchase|exists:stores,id',
            'quantity.*.amount' => 'required_if:type,purchase|integer',
        ]);

        $validator->sometimes('quantity', 'array', function ($input) {
            return $input->type === 'purchase';
        });

        $validator->sometimes('quantity', 'integer', function ($input) {
            return $input->type === 'sale';
        });

        $validator->validate();

        try {
            if (TransactionType::from($request->input('type')) === TransactionType::PURCHASE) {
                // Get sum of quantities user is transacting
                $quantity = 0;
                foreach ($request->input('quantity') as $q) {
                    $quantity += $q['amount'];
                }

                // Create a new Transaction
                $transaction = Transaction::create([
                    'invoice' => $request->input('invoice'),
                    'book_id' => $request->input('book_id'),
                    'transaction_on' => $request->input('transaction_on'),
                    'type' => $request->input('type'),
                    'quantity' => $quantity,
                ]);

                foreach ($request->input('quantity') as $q) {
                    $storeBook = StoreBook::firstOrCreate([
                        'store_id' => $q['store_id'],
                        'book_id' => $request->input('book_id'),
                    ]);

                    $storeBook->balance += $q['amount'];
                    $storeBook->save();

                    StoreTransaction::create([
                        'store_id' => $q['store_id'],
                        'transaction_id' => $transaction->id,
                        'quantity' => $q['amount'],
                    ]);
                }
            } else {
                $onShelf = StoreBook::firstOrCreate([
                    'book_id' => $request->input('book_id'),
                    'store_id' => Store::primary()->id,
                ])->balance;

                if ($onShelf < $request->input('quantity')) {
                    return response([
                        'message' => 'error',
                        'data' => 'Sale quantity exceeds primary store balance',
                    ], 422);
                }

                $transaction = Transaction::create([
                    'invoice' => $request->input('invoice'),
                    'book_id' => $request->input('book_id'),
                    'transaction_on' => $request->input('transaction_on'),
                    'type' => $request->input('type'),
                    'quantity' => $request->input('quantity'),
                ]);

                $storeBook = StoreBook::where([
                    'store_id' => Store::primary()->id,
                    'book_id' => $request->input('book_id'),
                ])->first();

                $storeBook->balance = $storeBook->balance - $request->input('quantity');
                $storeBook->save();

                StoreTransaction::create([
                    'store_id' => Store::primary()->id,
                    'transaction_id' => $transaction->id,
                    'quantity' => $request->input('quantity'),
                ]);
            }
        } catch (Exception $exception) {
            return response([
                'message' => 'error',
                'data' => $exception->getMessage(),
            ], 500);
        }

        return response([
            'message' => 'ok',
            'data' => $transaction,
        ], 200);
    }

    public function update(Request $request): Response|Application|ResponseFactory
    {
        $request->validate([
            'id' => 'required|exists:transactions,id',
            'invoice' => 'required',
            'transaction_on' => 'required|date_format:d-m-Y',
            'type' => [
                'required',
                new Enum(TransactionType::class),
            ],
            'quantity' => 'required|integer',
        ]);

        try {
            $transaction = Transaction::find($request->input('id'));
            $book = $transaction->book;

            // Handle case when updated sale exceeds balance
            if (TransactionType::from($request->input('type')) === TransactionType::SALE) {
                $resetBalance = match ($transaction->type) {
                    TransactionType::SALE => $transaction->book->balance + $transaction->quantity,
                    TransactionType::PURCHASE => $transaction->book->balance - $transaction->quantity
                };

                if ($request->input('quantity') > $resetBalance) {
                    return response([
                        'message' => 'error',
                        'data' => 'Sale cannot exceed current book balance ('.$resetBalance.')',
                    ]);
                }
            }

            $transaction->update(
                $request->only([
                    'invoice', 'transaction_on',
                    'type', 'quantity',
                ])
            );
        } catch (Exception $exception) {
            return response([
                'message' => 'error',
                'data' => $exception->getMessage(),
            ]);
        }

        return response([
            'message' => 'ok',
            'data' => $transaction,
        ]);
    }
}
