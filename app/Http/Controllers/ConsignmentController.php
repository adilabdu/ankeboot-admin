<?php

namespace App\Http\Controllers;

use App\Enums\PurchaseType;
use App\Http\Requests\ConsignmentReturnRequest;
use App\Models\Book;
use App\Models\ConsignmentReturn;
use App\Models\ConsignmentSettlement;
use App\Models\StoreBook;
use App\Models\Transaction;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ConsignmentController extends Controller
{
    public function index(Request $request): Response|Application|ResponseFactory
    {
        $request->validate([
            'id' => 'nullable|exists:consignment_settlements,id|prohibits:book_id',
            'book_id' => 'nullable|exists:books,id',
        ]);

        try {
            if ($request->has('id')) {
                $consignmentRecords = ConsignmentSettlement::find($request->input('id'));
            } elseif ($request->has('book_id')) {
                if (PurchaseType::from(Book::find($request->input('book_id'))->type) === PurchaseType::CASH) {
                    return response([
                        'message' => 'error',
                        'data' => 'Cash purchase books cannot be consigned',
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
                'body' => $exception->getMessage(),
            ]);
        }

        return response([
            'message' => 'ok',
            'body' => $consignmentRecords,
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
                'data' => $exception->getMessage(),
            ], 500);
        }

        return response([
            'message' => 'ok',
            'data' => $consignments,
        ]);
    }

    public function history(Request $request): Response|Application|ResponseFactory
    {
        // TODO: get all the `transaction` and `settlement` history of a book
        $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        try {
            $book = Book::find($request->input('book_id'));

            if (PurchaseType::from($book->type) === PurchaseType::CASH) {
                return response([
                    'message' => '"'.$book->title.'" is a cash purchase',
                ], 422);
            }

            $transactions = Transaction::where(
                'book_id', $request->input('book_id')
            )->get();

            $returns = ConsignmentReturn::where(
                'book_id', $request->input('book_id')
            )->get()->each(function ($return) {
                $return->type = 'return'; // For each record, add `type: return`
            });

            $settlements = ConsignmentSettlement::where(
                'book_id', $request->input('book_id')
            )->get()->each(function ($settlement) {
                $settlement->type = 'settlement';   // For each record, add `type: settlement`
            });

            $consignmentHistory = $transactions
                ->concat($settlements)
                ->concat($returns)
                ->sortBy('transaction_on')
                ->values()
                ->all();
        } catch (Exception $exception) {
            return response([
                'message' => 'error',
                'body' => $exception->getMessage(),
            ], 500);
        }

        return response([
            'message' => 'ok',
            'body' => [
                'book' => Book::with('supplier')->find($request->input('book_id')),
                'history' => $consignmentHistory,
                'payable' => $book->payable(),
                'settled' => $book->settled(),
            ],
        ]);
    }

    public function settle(Request $request): Response|Application|ResponseFactory
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'quantity' => 'required|integer|min:1',
            'transaction_on' => 'required|date_format:d-m-Y',
            'receipt' => 'string',
        ]);

        try {
            $max_payable = Book::find($request->input('book_id'))->max_payable();

            if ($request->input('quantity') > $max_payable) {
                return response([
                    'message' => 'error',
                    'data' => 'Maximum payable quantity is '.$max_payable,
                ], 422);
            }

            $consignmentRecord = ConsignmentSettlement::create($request->all());
        } catch (Exception $exception) {
            return response([
                'message' => 'error',
                'body' => $exception->getMessage(),
            ]);
        }

        return response([
            'message' => 'ok',
            'body' => $consignmentRecord,
        ]);
    }

    public function return(ConsignmentReturnRequest $request): Response|Application|ResponseFactory
    {
        try {
            DB::beginTransaction();

            $sum = 0;

            foreach ($request->input('quantity') as $store) {
                $sum += $store['amount'];

                $storeBook = StoreBook::where([
                    'store_id' => $store['store_id'],
                    'book_id' => $request->input('book_id'),
                ])->first();

                $storeBook->balance = $storeBook->balance - $store['amount'];
                $storeBook->save();
            }

            $consignmentRecord = ConsignmentReturn::create(
                array_merge($request->only('book_id', 'transaction_on', 'receipt'), [
                    'quantity' => $sum,
                ])
            );

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            return response([
                'message' => 'error',
                'body' => $exception->getMessage(),
            ]);
        }

        return response([
            'message' => 'ok',
            'body' => $consignmentRecord,
        ]);
    }
}
