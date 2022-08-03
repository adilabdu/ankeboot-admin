<?php

namespace App\Http\Controllers;

use App\Enums\ActivityType;
use App\Enums\PurchaseType;
use App\Enums\TransactionType;
use App\Models\Book;
use App\Models\Log;
use App\Models\Transaction;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Enum;

class BookController extends Controller
{

    public function index(Request $request): Response|Application|ResponseFactory
    {

        $request->validate([
            'id' => 'integer|exists:books'
        ]);

        try {
            if ($request->has('id')) {
                $books = Book::with('supplier')
                    ->with('transactions')
                    ->where('id', $request->input('id'))->first();
            } else {
                $books = Book::all();
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
            'code' => 'required|unique:books,code',
            'type' => [
                'required',
                new Enum(PurchaseType::class)
            ],
            'title' => 'required|string',
            'author' => 'string|nullable',
            'category' => 'string',
            'supplier_id' => 'exists:suppliers,id',
            'transaction_invoice' => 'required_with_all:transaction_on,transaction_type,transaction_quantity|string',
            'transaction_on' => 'required_with_all:transaction_invoice,transaction_type,transaction_quantity|date_format:d-m-Y',
            'transaction_type' => [
                'required_with_all:transaction_invoice,transaction_on,transaction_quantity',
                new Enum(TransactionType::class)
            ],
            'transaction_quantity' => 'required_with_all:transaction_invoice,transaction_on,transaction_type|integer'
        ], [
            'code.required' => 'The code field is required',
            'type.required' => 'The type field is required',
            'title.required' => 'The title field is required',
            'code.unique' => 'The code has already been taken',
            'type.Illuminate\Validation\Rules\Enum' =>
                'Invalid type field specified. Valid types are `consignment` or `cash`',
            'title.string' => 'The title field needs to be a string',
            'author.string' => 'The author field needs to be a string',
            'category.string' => 'The category field needs to be a string',
            'supplier_id.exists:suppliers,id' => 'A supplier with the given id does not exist'
        ]);

        try {

            DB::beginTransaction();

            $book_data = array_merge(
                $request->only([
                    'code', 'type', 'title',
                    'author', 'category'
                ]),
                ['balance' => 0]
            );
            $book = Book::create($book_data);

            if ($request->has('transaction_invoice')) {
                $book->transactions()
                    ->save(new Transaction([
                        'invoice' => $request->input('transaction_invoice'),
                        'transaction_on' => $request->input('transaction_on'),
                        'type' => $request->input('transaction_type'),
                        'quantity' => $request->input('transaction_quantity'),
                    ])
                );
            }

            DB::commit();

        } catch (Exception $exception) {

            DB::rollBack();

            return response([
                'message' => 'error',
                'data' => $exception->getMessage()
            ]);

        }

        return response([
            'message' => 'ok',
            'data' => Book::with('transactions')
                ->where('id', $book->id)
                ->first()
        ]);
    }

    public function categories(): Response|Application|ResponseFactory
    {

        try {

            $categories = Book::pluck('category')->unique()->values();

        } catch (Exception $exception) {

            return response([
                'message' => 'error',
                'data' => $exception->getMessage()
            ], 500);

        }

        return response([
            'message' => 'ok',
            'data' => $categories
        ]);

    }

}
