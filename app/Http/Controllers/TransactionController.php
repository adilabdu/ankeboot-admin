<?php

namespace App\Http\Controllers;

use App\Enums\TransactionType;
use App\Models\Book;
use App\Models\Transaction;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rules\Enum;

class TransactionController extends Controller
{

    public function index(Request $request): Response|Application|ResponseFactory
    {

        $request->validate([
            'id' => 'integer|exists:transactions'
        ]);

        try {
            if ($request->has('id')) {
                $books = Transaction::find($request->input('id'));
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
                    ]);

                }
            }

            $transaction = Transaction::create($request->all());

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
