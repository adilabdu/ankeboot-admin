<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Supplier;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'id' => 'nullable|exists:suppliers,id',
        ]);

        try {
            $suppliers = $request->has('id') ?
                Supplier::where('id', $request->input('id'))->first() :
                Supplier::all();
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'data' => $suppliers,
        ], 200);
    }

    public function post(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required_without:email|string',
            'email' => 'required_without:phone|email',
            'tin_number' => 'nullable|string|unique:suppliers,tin_number',
            'books_id' => 'nullable|array',
            'books_id.*' => 'nullable|exists:books,id',
        ]);

        try {
            DB::beginTransaction();

            $supplier = Supplier::create($request->only([
                'name',
                'phone',
                'email',
                'tin_number',
            ]));

            if ($request->has('books_id')) {
                foreach ($request->input('books_id') as $book_id) {
                    $supplier->books()->save(Book::find($book_id));
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            return response([
                'message' => 'error',
                'data' => $e->getMessage(),
            ], 500);
        }

        return response([
            'message' => 'success',
            'data' => $supplier->with('books')->find($supplier->id),
        ], 200);
    }
}
