<?php

namespace App\Http\Controllers;

use App\Enums\PurchaseType;
use App\Enums\TransactionType;
use App\Helpers\Helper;
use App\Models\Book;
use App\Models\Credit;
use App\Models\DailySale;
use App\Models\Deposit;
use App\Models\SalesReceipt;
use App\Models\MailingList;
use App\Models\Store;
use App\Models\StoreBook;
use App\Models\StoreTransaction;
use App\Models\Transaction;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

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
                new Carbon('Last month'))
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

    public function book(Request $request): Response|Application|ResponseFactory
    {

        /**
         * TODO: Write statements for these queries
         *
         * 1. Total quantity of books purchased
         * 2. Total quantity sold
         * 3. Sold this month (Change from last month)
         */

        $request->validate([
            'id' => 'required|exists:books,id'
        ]);

        try {

            $transactions = Book::find($request->input('id'))->transactions;
            $sold_last_month = $transactions->filter(function ($t) {
                return $t->type === TransactionType::SALE &&
                    $t->transaction_on >= Carbon::today()->subMonth();
            })->sum('quantity');
            $sold_before_last_month = $transactions->filter(function ($t) {
                return $t->type === TransactionType::SALE &&
                    $t->transaction_on >= Carbon::today()->subMonth()->subMonth() &&
                    $t->transaction_on < Carbon::today()->subMonth();
            })->sum('quantity');

            return response([
                'message' => 'ok',
                'data' => [
                    'total_sold' => $transactions->filter(function ($t) {
                        return $t->type === TransactionType::SALE;
                    })->sum('quantity'),
                    'total_purchased' => $transactions->filter(function ($t) {
                        return $t->type === TransactionType::PURCHASE;
                    })->sum('quantity'),
                    'sold_last_month' => [
                        'quantity' => $sold_last_month,
                        'change_rate' => $sold_before_last_month > 0 ?
                            (1 - ($sold_last_month / $sold_before_last_month)) :
                            null
                    ],
                ]
            ]);

        } catch (Exception $exception) {

            return response([
                'message' => 'error',
                'data' => $exception->getMessage()
            ], 500);

        }

    }

    public function sales(): Response|Application|ResponseFactory
    {

        // Get sales of the last 12 months
        $response = [
            'by_items' => [],
            'by_receipts' => []
        ];

        $months = [
            'jan', 'feb', 'mar',
            'apr', 'may', 'jun',
            'jul', 'aug', 'sep',
            'oct', 'nov', 'dec'
        ];

        $month = Carbon::today()->month;
        $year = Carbon::today()->year;

        try {

            $transactions = Transaction::all();
            $dailySales = SalesReceipt::all();

            for ($m = $month; $m >= ($month - 11); $m--) {

                $curr_month = $months[Helper::monthMapper($m)];

                $response['by_items'][$curr_month] =
                    $transactions->filter(function ($t) use ($m, $year) {
                        return ($t->transaction_on->month === (Helper::monthMapper($m) + 1)) &&
                            ($t->transaction_on->year === Helper::yearMapper($m, $year)) &&
                            ($t->type === TransactionType::SALE);
                    })->sum('quantity');

                $response['by_receipts'][$curr_month] =
                    $dailySales->filter(function ($d) use ($m, $year) {
                        return ($d->daily_sale->date_of->month === (Helper::monthMapper($m) + 1)) &&
                            ($d->daily_sale->date_of->year === Helper::yearMapper($m, $year));
                    })->sum('amount');

            }

        } catch (Exception $exception) {

            return response([
                'message' => 'error',
                'data' => $exception->getMessage()
            ], 500);

        }

        return response([
            'message' => 'ok',
            'data' => $response
        ]);

    }

    public function consignmentSales(): Response|Application|ResponseFactory
    {

        // Get all the transactions count within the last year
        // Filter and count the consignment sales
        // Return ratio

        try {

            $salesLastYear = Transaction::with('book')
                ->where('transaction_on', '>', Carbon::today()->subYear())
                ->get()
                ->filter(function ($transaction) {
                    return $transaction->type === TransactionType::SALE;
                });

            if ($salesLastYear->count() > 0) {

                $consignmentSalesLastYear = $salesLastYear->filter(function ($transaction) {
                    return $transaction->book->type === 'consignment' && $transaction->type === TransactionType::SALE;
                });

            } else {

                return response([
                    'message' => 'ok',
                    'data' => 0
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
            'data' => $consignmentSalesLastYear->count() / $salesLastYear->count()
        ]);

    }

    public function purchases(): Response|Application|ResponseFactory
    {

        // Get sales of the last 12 months
        $response = [];

        $months = [
            'jan', 'feb', 'mar',
            'apr', 'may', 'jun',
            'jul', 'aug', 'sep',
            'oct', 'nov', 'dec'
        ];

        $month = Carbon::today()->month;
        $year = Carbon::today()->year;

        try {

            $transactions = Transaction::all();

            for ($m = $month; $m >= ($month - 11); $m--) {

                $curr_month = $months[Helper::monthMapper($m)];

                $response[$curr_month] =
                    $transactions->filter(function ($t) use ($m, $year) {
                        return ($t->transaction_on->month === (Helper::monthMapper($m) + 1)) &&
                            ($t->transaction_on->year === Helper::yearMapper($m, $year)) &&
                            ($t->type === TransactionType::PURCHASE);
                    })->sum('quantity');

            }

        } catch (Exception $exception) {

            return response([
                'message' => 'error',
                'data' => $exception->getMessage()
            ], 500);

        }

        return response([
            'message' => 'ok',
            'data' => $response
        ]);

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

    public function mailingList(): Response|Application|ResponseFactory
    {

        // TODO: Run these queries
        // 1. Mailing List count
        // 2. Mailing List count by date (last 30 days)

        try {

            $mailing_list_count = MailingList::count();
            $entries_last_month = MailingList::where(
                    'created_at',
                    '>=',
                    new Carbon('Last month')
                )->count();

        } catch (Exception $exception) {

            return response([
                'message' => 'error',
                'data' => $exception->getMessage()
            ], 500);

        }

        return response([
            'message' => 'ok',
            'data' => [
                'count' => $mailing_list_count,
                'count_last_month' => $entries_last_month
            ]
        ], 200);

    }

    public function store(Request $request): Response|Application|ResponseFactory
    {

        $request->validate([
            'store_id' => 'required|exists:stores,id'
        ]);

        try {

            $latest = StoreTransaction::with('transaction', 'transaction.book')
                ->where('store_id', $request->input('store_id'))
                ->get()
                ->sortByDesc('transaction.transaction_on')
                ->first();

            $total_copies = StoreBook::where('store_id', $request->input('store_id'))->sum('balance');

            return response([
                'message' => 'ok',
                'data' => [
                    'total_copies' => $total_copies,
                    'books_count' => $total_copies === 0 ? 0 : Store::where('id', $request->input('store_id'))->first()->books->count(),
                    'latest_transaction' => $latest ? [
                        'book_title' => $latest->transaction->book->title,
                        'type' => $latest->transaction->type,
                        'quantity' => $latest->quantity,
                        'on' => $latest->transaction->transaction_on
                    ] : null
                ]
            ]);

        } catch (Exception $exception) {

            return response([
                'message' => 'error',
                'data' => $exception->getMessage()
            ], 500);

        }

    }

}
