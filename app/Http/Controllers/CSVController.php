<?php

namespace App\Http\Controllers;

use App\Enums\PurchaseType;
use App\Models\Book;
use App\Traits\ReadsCSV;
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

    public function insertTransactions(Request $request)
    {

        // TODO: insert transaction records from CSV input

    }

}
