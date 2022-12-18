<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Store;
use App\Models\StoreBook;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{

    public function index(Request $request): Response|Application|ResponseFactory
    {
        $request->validate([
            'id' => 'nullable|exists:stores,id'
        ]);

        try {
            $stores = ($request->has('id')) ?
                Store::with('address')->find($request->input('id')) :
                Store::with('address')->get();

        } catch (Exception $e) {

            return response([
                'message' => 'error',
                'data' => $e->getMessage()
            ], 500);

        }

        return response([
            'message' => 'ok',
            'data' => $stores
        ], 200);

    }

    public function post(Request $request): Response|Application|ResponseFactory
    {
        $request->validate([
            'name' => 'required|unique:stores',
            'primary' => 'boolean|nullable',
            'address_id' => 'required_without:address|integer|exists:addresses|nullable',
            'address' => 'required_without:address_id',
            'address.country' => 'required|string',
            'address.city' => 'required|string',
            'address.sub_city' => 'required|string',
            'address.kebele' => 'required|string',
            'address.house_number' => 'nullable|string',
        ]);

        try {

            DB::beginTransaction();

            $newAddress = Address::create([
                'country' => $request->input('address.country'),
                'city' => $request->input('address.city'),
                'sub_city' => $request->input('address.sub_city'),
                'kebele' => $request->input('address.kebele'),
                'house_number' => $request->input('address.house_number'),
            ]);

            $store = new Store();
            $store->name = $request->input('name');
            $store->primary = $request->input('primary');
            $store->address_id = $newAddress->id;
            $store->save();

            DB::commit();

        } catch (Exception $exception) {

            DB::rollBack();

            return response([
                'message' => 'error',
                'data' => $exception->getMessage(),
            ], 500);
        }

        return response([
            'message' => 'success',
            'data' => $store,
        ], 200);

    }

    public function list(Request $request): Response|Application|ResponseFactory
    {

        $request->validate([
            'store_id' => 'required|exists:stores,id'
        ]);

        try {

            $storeList = StoreBook::with('book')->where([
                'store_id' => $request->input('store_id')
            ])->get();

        } catch (Exception $exception) {

            return response([
                'message' => 'error',
                'data' => $exception->getMessage()
            ], 500);

        }

        return response([
            'message' => 'ok',
            'data' => $storeList
        ], 200);

    }
}
