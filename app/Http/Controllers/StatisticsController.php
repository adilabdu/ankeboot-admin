<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

class StatisticsController extends Controller
{

    public function books(): Response|Application|ResponseFactory
    {

        // TODO: Run these queries
        // - Total books record
        // - Total books record by purchase_type
        // - Total distinct books category
        // - Books count filtered by date (last 30 days)

        try {

            $books_count = Book::count();
            $consignment_count = Book::where('type', 'consignment')->count();
            $books_count_by_type = [
                'consignment' => $consignment_count,
                'cash' => $books_count - $consignment_count
            ];
            $categories_count = Book::all()->pluck('category')->unique()->count();
            $entries_last_month = Book::where(
                'created_at',
                '>=',
                new Carbon('yesterday'))
                ->count();

            return response([
                'message' => 'ok',
                'data' => [
                    'count' => $books_count,
                    'count_by_type' => $books_count_by_type,
                    'categories_count' => $categories_count,
                    'count_last_month' => $entries_last_month
                ]
            ], 200);


        } catch (Exception $exception) {

            return response([
                'message' => 'error',
                'data' => $exception->getMessage()
            ]);

        }

    }

}
