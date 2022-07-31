<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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


}
