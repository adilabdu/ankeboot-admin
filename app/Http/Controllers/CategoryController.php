<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Exception;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = Book::pluck('category')->unique()->values();
        } catch (Exception $exception) {
            return response([
                'message' => 'error',
                'data' => $exception->getMessage(),
            ], 500);
        }

        return response([
            'message' => 'ok',
            'data' => $categories,
        ], 200);
    }
}
