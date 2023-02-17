<?php

namespace App\Http\Controllers;

use App\Enums\PurchaseType;
use App\Enums\TransactionType;
use App\Models\Book;
use App\Models\StoreBook;
use App\Models\Transaction;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Meilisearch\Endpoints\Indexes;

class BookController extends Controller
{
    public function index(Request $request): Response|Application|ResponseFactory
    {
        $request->validate([
            'id' => 'integer|exists:books',
        ]);

        try {
            if ($request->has('id')) {
                $books = Book::with('supplier')
                    ->with('transactions')
                    ->find($request->input('id'));
            } else {
                $books = Book::all();
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

    public function paginate(Request $request): Response|Application|ResponseFactory
    {
        $request->validate([
            'query' => 'nullable|string',
            'page' => 'integer',
            'per_page' => 'integer',
            'order_by' => 'nullable|string',
            'from_date' => 'nullable|int',
            'to_date' => 'nullable|int',
            'desc' => 'nullable|boolean',
        ]);

        try {
            return response([
                'message' => 'success',
                'data' => Book::search($request->input('query', ''), function (Indexes $meilisearch, string $query, array $options) use ($request) {
                    $options['filter'] = 'created_at >= ' . ($request->input('from_date', Carbon::createFromDate('01-01-1970')->timestamp)) . ' AND created_at <= ' . ($request->input('to_date', Carbon::createFromDate('01-01-3023')->timestamp));
                    return $meilisearch->search($query, $options);
                })->orderBy(
                    $request->input('order_by') ?? 'id',
                    $request->input('desc') ? 'desc' : 'asc'
                )->paginate($request->input('per_page'))->withQueryString(),
            ]);
        } catch (Exception $exception) {
            return response([
                'message' => 'error',
                'data' => $exception->getMessage(),
            ], 500);
        }
    }

    public function stores(Request $request): Response|Application|ResponseFactory
    {
        $request->validate([
            'id' => 'required|exists:books,id',
        ]);

        try {
            $stores = StoreBook::with('store')
                ->where('book_id', $request->input('id'))
                ->get();
        } catch (Exception $exception) {
            return response([
                'message' => 'error',
                'data' => $exception->getMessage(),
            ], 500);
        }

        return response([
            'message' => 'success',
            'data' => $stores,
        ]);
    }

    public function post(Request $request): Response|Application|ResponseFactory
    {
        $request->validate([
            'code' => 'required|unique:books,code',
            'type' => [
                'required',
                new Enum(PurchaseType::class),
            ],
            'title' => 'required|string',
            'author' => 'string|nullable',
            'category' => 'string',
            'supplier_id' => 'exists:suppliers,id',
            'transaction_invoice' => 'required_with_all:transaction_on,transaction_type,transaction_quantity|string',
            'transaction_on' => 'required_with_all:transaction_invoice,transaction_type,transaction_quantity|date_format:d-m-Y',
            'transaction_type' => [
                'required_with_all:transaction_invoice,transaction_on,transaction_quantity',
                new Enum(TransactionType::class),
            ],
            'transaction_quantity' => 'required_with_all:transaction_invoice,transaction_on,transaction_type|integer',
        ], [
            'code.required' => 'The code field is required',
            'type.required' => 'The type field is required',
            'title.required' => 'The title field is required',
            'code.unique' => 'The code has already been taken',
            'type.Illuminate\Validation\Rules\Enum' => 'Invalid type field specified. Valid types are `consignment` or `cash`',
            'title.string' => 'The title field needs to be a string',
            'author.string' => 'The author field needs to be a string',
            'category.string' => 'The category field needs to be a string',
            'supplier_id.exists:suppliers,id' => 'A supplier with the given id does not exist',
        ]);

        try {
            DB::beginTransaction();

            $book_data = array_merge(
                $request->only([
                    'code', 'type', 'title',
                    'author', 'category', 'supplier_id'
                ]),
            );
            $book = Book::make($book_data);
            $book->balance = 0;
            $book->save();

//            // TODO: fix transaction creation logic
//            if ($request->has('transaction_invoice')) {
//                $transaction = $book->transactions()
//                    ->save(new Transaction([
//                        'invoice' => $request->input('transaction_invoice'),
//                        'transaction_on' => $request->input('transaction_on'),
//                        'type' => TransactionType::PURCHASE,
//                        'quantity' => $request->input('transaction_quantity'),
//                    ])
//                    );
//            }

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            return response([
                'message' => 'error',
                'data' => $exception->getMessage(),
            ], 500);
        }

        return response([
            'message' => 'ok',
            'data' => Book::with('transactions')
                ->where('id', $book->id)
                ->first(),
        ], 201);
    }

    public function update(Request $request): Response|Application|ResponseFactory
    {
        $request->validate([
            'id' => 'required|exists:books,id',
            'code' => [
                'required',
                Rule::unique('books')->ignore(Book::find($request->input('id')), 'code'),
            ],
            'type' => [
                'required',
                new Enum(PurchaseType::class),
            ],
            'title' => 'required|string',
            'alternate_title' => 'string|nullable',
            'author' => 'string|nullable',
            'category' => 'string',
            'supplier_id' => 'exists:suppliers,id',
        ], [
            'id.required' => 'The id field is required',
            'code.required' => 'The code field is required',
            'type.required' => 'The type field is required',
            'title.required' => 'The title field is required',
            'code.unique' => 'The code has already been taken',
            'type.Illuminate\Validation\Rules\Enum' => 'Invalid type field specified. Valid types are `consignment` or `cash`',
            'title.string' => 'The title field needs to be a string',
            'author.string' => 'The author field needs to be a string',
            'category.string' => 'The category field needs to be a string',
            'id.exists' => 'A book with the given id does not exist',
            'supplier_id.exists:suppliers,id' => 'A supplier with the given id does not exist',
        ]);

        try {
            DB::beginTransaction();

            $book = Book::find($request->input('id'));
            $book->update(
                $request->only([
                    'code', 'type', 'title', 'alternate_title',
                    'author', 'category', 'supplier_id',
                ])
            );

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            return response([
                'message' => 'error',
                'data' => $exception->getMessage(),
            ], 500);
        }

        return response([
            'message' => 'ok',
            'data' => Book::with('transactions')
                ->where('id', $book->id)
                ->first(),
        ], 200);
    }

    public function delete(Request $request): Response|Application|ResponseFactory
    {
        $request->validate([
            'id' => 'required|exists:books,id',
        ]);

        try {
            $book = Book::find($request->input('id'))
                ->delete();
        } catch (Exception $e) {
            return response([
                'message' => 'error',
                'data' => $e->getMessage(),
            ], 500);
        }

        return response([
            'message' => 'success',
            'data' => $book,
        ], 200);
    }
}
