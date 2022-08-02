<?php

namespace App\Http\Controllers;

use App\Enums\ActivityType;
use App\Enums\PurchaseType;
use App\Models\Book;
use App\Models\Log;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
            'author' => 'string',
            'category' => 'string',
            'balance' => 'required|numeric',
            'supplier_id' => 'exists:suppliers,id'
        ], [
            'code.required' => 'The code field is required',
            'type.required' => 'The type field is required',
            'title.required' => 'The title field is required',
            'balance.required' => 'The balance field is required',
            'code.unique' => 'The code has already been taken',
            'type.Illuminate\Validation\Rules\Enum' =>
                'Invalid type field specified. Valid types are `consignment` or `cash`',
            'title.string' => 'The title field needs to be a string',
            'author.string' => 'The author field needs to be a string',
            'category.string' => 'The category field needs to be a string',
            'balance.numeric' => 'The balance field needs to be a number',
            'supplier_id.exists:suppliers,id' => 'A supplier with the given id does not exist'
        ]);

        try {

            $book = Book::create($request->all());

        } catch (Exception $exception) {

            return response([
                'message' => 'error',
                'data' => $exception->getMessage()
            ]);

        }

        return response([
            'message' => 'ok',
            'data' => $book
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
