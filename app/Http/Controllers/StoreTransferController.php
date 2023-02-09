<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\StoreBook;
use App\Models\StoreTransfer;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class StoreTransferController extends Controller
{
    public function create(Request $request): Response|Application|ResponseFactory
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'from' => 'required|exists:stores,id',
            'to' => 'nullable|exists:stores,id|different:from',
            'quantity' => 'required|integer',
        ]);

        $to = $request->input('to') ?? Store::primary()->id;

        try {
            $outgoingStore = StoreBook::where([
                'store_id' => $request->input('from'),
                'book_id' => $request->input('book_id'),
            ])->first();

            if (
                $outgoingStore?->balance < $request->input('quantity')
            ) {
                return response([
                    'message' => 'error',
                    'data' => 'Balance in store is below requested transfer amount',
                ], 422);
            }

            DB::beginTransaction();

            $storeTransfer = StoreTransfer::create([
                'book_id' => $request->input('book_id'),
                'from' => $request->input('from'),
                'to' => $to,
                'quantity' => $request->input('quantity'),
            ]);
            $incomingStore = StoreBook::firstOrCreate([
                'store_id' => $to,
                'book_id' => $request->input('book_id'),
            ]);

            // Update Store records
            $outgoingStore->balance = $outgoingStore->balance - $request->input('quantity');
            $incomingStore->balance += $request->input('quantity');

            $outgoingStore->save();
            $incomingStore->save();

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            return response([
                'message' => 'error',
                'data' => $exception->getMessage(),
            ], 500);
        }

        return response([
            'message' => 'ok',
            'data' => $storeTransfer,
        ], 200);
    }

    public function balance(Request $request): Response|Application|ResponseFactory
    {
        $request->validate([
            'book_id' => 'required_without:store_id|exists:books,id',
            'store_id' => 'required_without:book_id|exists:stores,id',
        ]);

        try {
            if ($request->has('book_id')) {
                $balance = StoreBook::where([
                    'book_id' => $request->input('book_id'),
                ]);

                if ($request->has('store_id')) {
                    $balance->where([
                        'book_id' => $request->input('book_id'),
                    ]);
                }
            } else {
                $balance = StoreBook::where([
                    'store_id' => $request->input('store_id'),
                ]);
            }

            $balance = $balance->first();

            return response([
                'message' => 'ok',
                'data' => $balance ? $balance->balance : 0,
            ]);
        } catch (Exception $exception) {
            return response([
                'message' => 'error',
                'data' => $exception->getMessage(),
            ], 500);
        }
    }
}
