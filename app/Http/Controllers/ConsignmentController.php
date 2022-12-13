<?php

namespace App\Http\Controllers;

use App\Enums\ConsignmentAction;
use App\Enums\PurchaseType;
use App\Models\Book;
use App\Models\ConsignmentSettlement;
use App\Models\Transaction;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ConsignmentController extends Controller
{
    public function index(Request $request): Response|Application|ResponseFactory
    {

        $request->validate([
            'id' => 'nullable|exists:consignment_settlements,id|prohibits:book_id',
            'book_id' => 'nullable|exists:books,id'
        ]);

        try {

            if ($request->has('id')) {

                $consignmentRecords = ConsignmentSettlement::find($request->input('id'));

            } else if ($request->has('book_id')) {

                if (PurchaseType::from(Book::find($request->input('book_id'))->type) === PurchaseType::CASH) {
                    return response([
                        'message' => 'error',
                        'data' => 'Cash purchase books cannot be consigned'
                    ], 400);
                }

                $consignmentRecords = ConsignmentSettlement::where(
                    'book_id',
                    $request->input('book_id')
                );

            } else {

                $consignmentRecords = ConsignmentSettlement::all();

            }

        } catch (Exception $exception) {

            return response([
                'message' => 'error',
                'body' => $exception->getMessage()
            ]);

        }

        return response([
            'message' => 'ok',
            'body' => $consignmentRecords
        ]);

    }

    public function books(): Response|Application|ResponseFactory
    {

        try {

            $consignments = Book::where(
                'type',
                PurchaseType::CONSIGNMENT
            )->get()->map(function ($consignment) {
                return [
                    'id' => $consignment->id,
                    'code' => $consignment->code,
                    'title' => $consignment->title,
                    'category' => $consignment->category,
                    'balance' => $consignment->balance,
                    'payable' => $consignment->payable(),
                    'settled' => $consignment->settled(),
                    'created_at' => $consignment->created_at,
                    'updated_at' => $consignment->updated_at,
                    'deleted_at' => $consignment->deleted_at,
                ];
            });

        } catch (Exception $exception) {

            return response([
                'message' => 'error',
                'data' => $exception->getMessage()
            ], 500);

        }

        return response([
            'message' => 'ok',
            'data' => $consignments
        ]);

    }

    public function history(Request $request) : Response|Application|ResponseFactory
    {
        // TODO: get all the `transaction` and `settlement` history of a book
        $request->validate([
            'book_id' => 'required|exists:books,id'
        ]);

        try {

            $book = Book::find($request->input('book_id'));

            if (PurchaseType::from($book->type) === PurchaseType::CASH) {
                return response([
                    'message' => '"' . $book->title . '" is a cash purchase'
                ], 422);
            }

            $transactions = Transaction::where(
                'book_id', $request->input('book_id')
            )->get();

            $settlements = ConsignmentSettlement::where(
                'book_id', $request->input('book_id')
            )->get()->each(function ($settlement) {
                $settlement->type = 'settlement';   // For each record, add `type: settlement`
            });

            $consignmentHistory = $transactions->merge($settlements)
                ->sortBy('transaction_on')
                ->values()
                ->all();

        } catch (Exception $exception) {

            return response([
                'message' => 'error',
                'body' => $exception->getMessage()
            ], 500);

        }

        return response([
            'message' => 'ok',
            'body' => [
                'book' => Book::with('supplier')->find($request->input('book_id')),
                'history' => $consignmentHistory,
                'payable' => $book->payable(),
                'settled' => $book->settled()
            ]
        ]);

    }

    public function create(Request $request): Response|Application|ResponseFactory
    {

        $request->validate([
            'book_id' => 'required|exists:books,id',
            'quantity' => 'required|integer|min:1',
            'transaction_on' => 'required|date_format:d-m-Y',
            'receipt' => 'string'
        ]);

        try {

            $payable = Book::find($request->input('book_id'))->payable();

            if ($request->input('quantity') > $payable) {

                return response([
                    'message' => 'error',
                    'data' => 'Maximum payable quantity is ' . $payable
                ]);

            }

            $consignmentRecord = ConsignmentSettlement::create($request->all());

        } catch (Exception $exception) {

            return response([
                'message' => 'error',
                'body' => $exception->getMessage()
            ]);

        }

        return response([
            'message' => 'ok',
            'body' => $consignmentRecord
        ]);

    }

}
