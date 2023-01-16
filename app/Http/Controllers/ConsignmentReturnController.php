<?php

namespace App\Http\Controllers;

use App\Enums\PurchaseType;
use App\Models\Book;
use App\Models\ConsignmentReturn;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ConsignmentReturnController extends Controller
{

    public function index(Request $request): Response|Application|ResponseFactory
    {

        $request->validate([
            'book_id' => 'exists:books,id'
        ]);

        try {

            if ($request->has('book_id')) {

                $book = Book::find($request->input('book_id'));

                if (PurchaseType::from($book->type) !== PurchaseType::CONSIGNMENT) {

                    return response([
                        'message' => 'error',
                        'data' => 'Cannot get return data for non-consignment items'
                    ], 422);

                }

                return response([
                    'message' => 'ok',
                    'data' => ConsignmentReturn::where('book_id', $request->input('book_id'))->get()
                ]);

            }

        } catch (Exception $exception) {

            return response([
                'message' => 'error',
                'data' => $exception->getMessage()
            ], 500);

        }

        return response([
            'message' => 'ok',
            'data' => ConsignmentReturn::all()
        ]);

    }

}
