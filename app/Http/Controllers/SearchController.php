<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SearchController extends Controller
{
    public function books(Request $request): Response|Application|ResponseFactory
    {
        $request->validate([
            'query' => 'required|string',
        ]);

        try {
            $results = Book::search(
                $request->input('query')
            )->get();
        } catch (Exception $exception) {
            return response([
                'message' => 'error',
                'data' => $exception->getMessage(),
            ], 500);
        }

        return response([
            'message' => 'ok',
            'data' => [
                'results' => $results,
                'count' => $results->count(),
            ],
        ], 200);
    }
}
