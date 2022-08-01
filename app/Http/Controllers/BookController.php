<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
}
