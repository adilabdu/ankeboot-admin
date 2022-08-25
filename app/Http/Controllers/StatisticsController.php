<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Credit;
use App\Models\DailySale;
use App\Models\Deposit;
use App\Models\SalesReceipt;
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

    public function dailySales(): Response|Application|ResponseFactory
    {

        // TODO: Run these queries
        // - Unsubmitted Daily Sales (count)
        // - Aggregate Daily Sales Difference
        //      - All time
        //      - Current fiscal year
        //      - Last month
        //      - Last week
        // - Aggregate Sales
        //      - All time
        //      - Current fiscal year
        //      - Last month
        //      - Last week
        // - Aggregate Deposit
        // - Unpaid Credit Sales

        $lastWeek = Carbon::today()->subWeek();
        $lastMonth = Carbon::today()->subMonth();

        try {

            // Unsubmitted
            $unsubmitted = DailySale::where('is_submitted', false)->count();
            $total = DailySale::count();

            // Aggregate Daily Sales Difference
            $difference = [
                'all' => DailySale::sum('difference'),
                'last_week' => DailySale::where(
                    'date_of', '>=', $lastWeek
                )->get()->sum('difference'),
                'last_month' => DailySale::where(
                    'date_of', '>=', $lastMonth
                )->get()->sum('difference')
            ];

            // Aggregate Sales
            $sales = [
                'all' => SalesReceipt::sum('amount'),
                'last_week' => SalesReceipt::all()
                    ->filter(function ($sale) use ($lastWeek) {
                        return $sale->daily_sale->date_of >=
                            $lastWeek;
                    })->sum('amount'),
                'last_month' => SalesReceipt::all()
                    ->filter(function ($sale) use ($lastMonth) {
                        return $sale->daily_sale->date_of >=
                            $lastMonth;
                    })->sum('amount')
            ];

            // Aggregate Deposits
            $deposits = [
                'all' => Deposit::sum('amount'),
                'last_week' => Deposit::all()
                    ->filter(function ($deposit) use ($lastWeek) {
                        return $deposit->daily_sale->date_of >=
                            $lastWeek;
                    })->sum('amount'),
                'last_month' => Deposit::all()
                    ->filter(function ($deposit) use ($lastMonth) {
                        return $deposit->daily_sale->date_of >=
                            $lastMonth;
                    })->sum('amount')
            ];

            // Unsettled Credit Sales
            $credits = [
                'all' => Credit::whereNull('settled_on')
                    ->get()
                    ->sum(function ($credit) {
                        return $credit->deposit->amount;
                    }),
                'last_week' => Credit::whereNull('settled_on')
                    ->get()->filter(function ($credit) use ($lastWeek) {
                        return $credit->deposit->daily_sale->date_of
                            >= $lastWeek;
                    })->sum(function ($credit) {
                        return $credit->deposit->amount;
                    }),
                'last_month' => Credit::whereNull('settled_on')
                    ->get()->filter(function ($credit) use ($lastMonth) {
                        return $credit->deposit->daily_sale->date_of
                            >= $lastMonth;
                    })->sum(function ($credit) {
                        return $credit->deposit->amount;
                    }),
            ];

        } catch (Exception $exception) {

            return response([
                'message' => 'error',
                'data' => $exception->getMessage()
            ]);

        }

        return response([
            'message' => 'ok',
            'data' => [
                'daily_sale' => [
                    'unsubmitted' => $unsubmitted,
                    'count' => $total
                ],
                'aggregate' => [
                    'difference' => $difference,
                    'sales' => $sales,
                    'deposits' => $deposits,
                    'credits' => $credits
                ]
            ]
        ]);

    }

}
