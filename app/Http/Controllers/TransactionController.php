<?php

namespace App\Http\Controllers;

use App\Enums\TransactionType;
use App\Models\Book;
use App\Models\DailySale;
use App\Models\Transaction;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rules\Enum;
use Carbon\Carbon;

class TransactionController extends Controller
{

    public function index(Request $request): Response|Application|ResponseFactory
    {

        $request->validate([
            'id' => 'integer|exists:transactions'
        ]);

        try {
            if ($request->has('id')) {
                $books = Transaction::with('book')->find($request->input('id'));
            } else {
                $books = Transaction::with('book')->get();
            }

            return response([
                'message' => 'ok',
                'data' => $books
            ], 200);

        } catch (Exception $e) {

            return response([
                'message' => 'error',
                'data' => $e->getMessage()
            ]);

        }
    }

    public function post(Request $request): Response|Application|ResponseFactory
    {

        $request->validate([
            'invoice' => 'required',
            'book_id' => 'required|exists:books,id',
            'transaction_on' => 'required|date_format:d-m-Y',
            'type' => [
                'required',
                new Enum(TransactionType::class)
            ],
            'quantity' => 'required|integer'
        ]);

        try {

            $book = Book::find($request->input('book_id'));
            if (TransactionType::from($request->input('type')) === TransactionType::SALE) {
                if ($request->input('quantity') > $book->balance) {

                    return response([
                        'message' => 'error',
                        'data' => 'Sale cannot exceed current book balance (' . $book->balance . ')'
                    ], 422);

                }
            }

            $transaction = Transaction::create($request->all());

            if ($transaction->type === TransactionType::SALE) {

                $dailySale = DailySale::where('date_of', Carbon::parse($transaction->transaction_on))->first();

                if ($dailySale) {

                    $dailySale->transactions()->save($transaction);

                } else {

                    return response([
                        'message' => 'error',
                        'data' => 'Daily sale not found'
                    ], 404);

                }

            }

        } catch (Exception $exception) {

            return response([
                'message' => 'error',
                'data' => $exception->getMessage()
            ], 500);

        }

        return response([
            'message' => 'ok',
            'data' => $transaction
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
                new Enum(TransactionType::class)
            ],
            'quantity' => 'required|integer'
        ]);

        try {

            $transaction = Transaction::find($request->input('id'));
            $book = $transaction->book;
           
            // Handle case when updated sale exceeds balance 
            if (TransactionType::from($request->input('type')) === TransactionType::SALE) {

                $resetBalance = match($transaction->type) {
                    TransactionType::SALE =>  $transaction->book->balance + $transaction->quantity,
                    TransactionType::PURCHASE =>  $transaction->book->balance - $transaction->quantity
                };

                if ($request->input('quantity') > $resetBalance) {
                    return response([
                        'message' => 'error',
                        'data' => 'Sale cannot exceed current book balance (' . $resetBalance . ')'
                    ]);
                }

            }

            $transaction->update(
                $request->only([
                    'invoice', 'transaction_on',
                    'type', 'quantity'
                ])
            );

        } catch (Exception $exception) {

            return response([
                'message' => 'error',
                'data' => $exception->getMessage()
            ]);

        }

        return response([
            'message' => 'ok',
            'data' => $transaction
        ]);
    }



}
