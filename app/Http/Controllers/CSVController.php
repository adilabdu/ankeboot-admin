<?php

namespace App\Http\Controllers;

use App\Enums\PurchaseType;
use App\Enums\TransactionType;
use App\Http\Requests\InsertDailySaleTransactionsRequest;
use App\Models\Book;
use App\Models\DailySale;
use App\Models\Transaction;
use App\Traits\ReadsCSV;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\File;

class CSVController extends Controller
{

    use ReadsCSV;

    public function insertBooks(Request $request): Response|Application|ResponseFactory
    {

        $request->validate([
            'file' => [
                'required',
                File::types(['csv'])
            ]
        ]);

        try {

            $records = $this->arrayFromCSV($request->file('file'));

            Validator::validate($records, [
                '*.code' => [
                    'required',
                ],
                '*.type' => [
                    'required',
                    new Enum(PurchaseType::class)
                ],
                '*.title' => 'required|string',
                '*.author' => 'string',
                '*.category' => 'string',
                '*.balance' => 'required|numeric'
            ], [
                '*.code.required' => 'The code field at #:position is required',
                '*.type.required' => 'The type field at #:position is required',
                '*.title.required' => 'The title field at #:position is required',
                '*.balance.required' => 'The balance field at #:position is required',
                '*.type.Illuminate\Validation\Rules\Enum' =>
                    'Invalid type field at #:position specified. Valid types are `consignment` or `cash`',
                '*.title.string' => 'The title field at #:position needs to be a string',
                '*.author.string' => 'The author field at #:position needs to be a string',
                '*.category.string' => 'The category field at #:position needs to be a string',
                '*.balance.numeric' => 'The balance field at #:position needs to be a number'
            ]);

            DB::beginTransaction();

            foreach ($records as $i => $record) {

                Validator::validate($record, [
                    'code' => 'unique:books,code'
                ], [
                    'code.unique' => 'The code at #'. $i + 1 . ' has already been taken'
                ]);

                Book::create($record);

            }

            DB::commit();

        } catch (Exception $e) {

            DB::rollBack();

            return response([
                'message' => 'error',
                'data' => $e->getMessage()
            ], 500);

        }

        return response([
            'message' => 'ok',
            'data' => count($records) . ' records inserted'
        ], 200);

    }

    public function insertTransactions(Request $request): Response|Application|ResponseFactory
    {

        // TODO: insert transaction records from CSV input

        $request->validate([
            'file' => [
                'required',
                File::types(['csv'])
            ],
            'transaction_date' => 'date'
        ]);

        try {

            $records = $this->arrayFromCSV($request->file('file'));

            Validator::validate($records, [
                '*.code' => 'required|exists:books,code',
                '*.quantity' => 'required|integer',
                '*.type' => [
                    'required',
                    new Enum(TransactionType::class)
                ],
                '*.invoice' => 'required|string',
                '*.transaction_date' => [
                    $request->has('transaction_date') ? '' : 'required'
                ]
            ], [
                '*.code.required' => 'The code field at #:position is required',
                '*.quantity.required' => 'The quantity field at #:position is required',
                '*.type.required' => 'The type field at #:position is required',
                '*.invoice.required' => 'The invoice field at #:position is required',
                '*.transaction_date.required' => 'The transaction_on field at #:position is required',
                '*.quantity.integer' => 'The quantity field at #:position must be a natural number',
                '*.code.exists:books,code' => 'A book with the code field at #:position does not exist',
                '*.type.Illuminate\Validation\Rules\Enum' =>
                    'Invalid type field at #:position specified. Valid types are `purchase` or `sale`'
            ]);

            DB::beginTransaction();

            foreach ($records as $record) {

                $book = Book::where('code', $record['code'])->first();

                Transaction::create([
                    'invoice' => $record['invoice'],
                    'book_id' => $book->id,
                    'transaction_on' => $request->input('transaction_date') ??
                        new Carbon($record['transaction_date']),
                    'type' => $record['type'],
                    'quantity' => $record['quantity']
                ]);

                // Implementation taken to TransactionObserver class instead
//                // Update book balance
//                if ($record['type'] === TransactionType::PURCHASE->value) {
//                    $book->balance += $record['quantity'];
//                } else {
//                    $book->balance += ($record['quantity'] * -1);
//                }
//                $book->save();

            }

            DB::commit();

        } catch (Exception $exception) {

            DB::rollBack();

            return response([
                'message' => 'error',
                'data' => $exception->getMessage()
            ], 500);

        }

        return response([
            'message' => 'ok',
            'data' => count($records) . ' transaction records inserted'
        ], 200);

    }

    public function insertDailySaleTransactions(Request $request): Response|Application|ResponseFactory
    {

        $request->validate([
            'file' => [
                'required',
                File::types(['csv'])
            ],
            'daily_sale_id' => 'required|exists:daily_sales,id'
        ]);

        try {

            $records = $this->arrayFromCSV($request->file('file'));

            Validator::validate($records, [
                '*.Item ID' => 'required|exists:books,code',
                '*.Quantity' => 'required|integer',
            ], [
                '*.Item ID.required' => 'The code field at #:position is required',
                '*.Quantity.required' => 'The quantity field at #:position is required',
                '*.Quantity.integer' => 'The quantity field at #:position must be a natural number',
                '*.Item ID.exists' => 'A book with the code field at #:position does not exist'
            ]);

            DB::beginTransaction();

            $counter = 0;
            $dailySale = DailySale::find($request->input('daily_sale_id'));
            foreach ($records as $record) {

                $counter += 1;
                $book = Book::where('code', $record['Item ID'])->first();

                $dailySale->transactions()->save(new Transaction([
                    'invoice' => 'ank' . $counter . Carbon::parse($dailySale['date_of'])->format('dmY'),
                    'book_id' => $book->id,
                    'transaction_on' => $dailySale['date_of'],
                    'type' => 'sale',
                    'quantity' => $record['Quantity']
                ]));

            }

            DB::commit();

        } catch (Exception $exception) {

            DB::rollBack();

            return response([
                'message' => 'error',
                'data' => $exception->getMessage()
            ], 500);

        }

        return response([
            'message' => 'ok',
            'data' => count($records) . ' transaction records inserted'
        ], 200);

    }

}
