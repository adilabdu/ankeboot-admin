<?php

namespace App\Http\Controllers;

use App\Enums\DepositType;
use App\Http\Requests\UpdateDailySaleRequest;
use App\Models\Credit;
use App\Models\DailySale;
use App\Models\Deposit;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DailySaleController extends Controller
{

    public function index(Request $request): Response|Application|ResponseFactory
    {

        $request->validate([
            'limit' => 'integer|prohibits:id',
            'id' => 'integer|exists:daily_sales,id|prohibits:limit'
        ]);

        try {

            if ($request->has('limit')) {

                $dailySales = DailySale::latest('date_of')
                    ->paginate($request->input('limit'));

            } else if ($request->has('id')) {

                $dailySales = DailySale::with([
                    'transactions.book', 'transactions', 'expenses', 'sales_receipts', 'deposits', 'deposits.credit'
                ])
                    ->find($request->input('id'));

            } else {

                $dailySales = DailySale::all();

            }

        } catch (Exception $exception) {

            return response([
                'message' => 'error',
                'data' => $exception->getMessage()
            ]);

        }

        return response([
            'message' => 'ok',
            'data' => $dailySales
        ]);

    }

    public function create(): Response|Application|ResponseFactory
    {

        $yesterday = Carbon::yesterday();
        $lastEntry = Carbon::createFromDate(
            DailySale::latest('date_of')
                ->first()
                ->date_of ??
            'August 09, 2022'
            );
        $count = 0;

        DB::beginTransaction();

        try {

            while ($yesterday > $lastEntry) {

                $lastEntry = $lastEntry->addDay();

                if (! $lastEntry->isSunday()) {

                    $count++;
                    $dailySale = new DailySale();
                    $dailySale['date_of'] = $lastEntry;
                    $dailySale->save();
                }

            }

            $unsubmitted = DailySale::where('is_submitted', false)->count();

            DB::commit();

        } catch (Exception $exception) {

            DB::rollBack();

            return response([
                'message' => 'error',
                'data' => $exception->getMessage()
            ]);

        }

        return response([
            'message' => 'ok',
            'data' => [
                'added_count' => $count,
                'unsubmitted_count' => $unsubmitted
            ]
        ]);

    }

    public function update(UpdateDailySaleRequest $request): Response|Application|ResponseFactory
    {

        DB::beginTransaction();

        try {

            $dailySale = DailySale::find($request->input('id'));

            if ($request->input('update_submitted')) {

                // Delete all the deposits
                // Delete all the sales receipts
                // Delete all the expenses

                foreach ($dailySale->deposits as $deposit) {

                    $deposit->delete();

                }

                foreach ($dailySale->sales_receipts as $salesReceipt) {

                    $salesReceipt->delete();

                }

                foreach ($dailySale->expenses as $expense) {

                    $expense->delete();

                }

            }

            // Once records are deleted, validate receipts are unique
            Validator::validate($request->all(), [
                'sales_receipts.*.receipt' => 'unique:sales_receipts,receipt',
                'expenses.*.receipt' => 'unique:daily_sale_expenses,receipt',
            ]);

            $collected = 0.00;
            $reported = 0.00;

            $dailySale->update([
                'cashier' => $request->input('cashier')
            ]);

            $dailySale->deposits()->createMany($request->input('deposits'));
            $dailySale->sales_receipts()->createMany($request->input('sales_receipts'));
            $dailySale->expenses()->createMany($request->input('expenses'));

            // TODO: move the `difference` calculations to an Observable (?)
            foreach ($request->input('credits') as $credit) {

                $dailySale->deposits()
                    ->save(new Deposit([
                        'amount' => $credit['amount'],
                        'type' => DepositType::CREDIT,
                        'deposited_on' => $dailySale['date_of'],
                    ]))
                    ->credit()->save(new Credit([
                        'receipt' => $credit['receipt'],
                        'client_name' => $credit['client_name']
                    ]));

                $collected += $credit['amount'];

            }

            foreach ($request->input('deposits') as $deposit) {
                $collected += $deposit['amount'];
            }

            foreach ($request->input('expenses') as $expense) {
                $collected += $expense['amount'];
            }

            foreach ($request->input('sales_receipts') as $sales_receipt) {
                $reported += $sales_receipt['amount'];
            }

            $dailySale->is_submitted = true;
            $dailySale->difference = round($collected - $reported, 2);
            $dailySale->save();


        } catch (Exception $exception) {

            DB::rollBack();

            return response([
                'message' => 'error',
                'data' => $exception->getMessage()
            ], 500);

        }

        DB::commit();

        return response([
            'message' => 'ok',
            'data' => $dailySale
        ], 200);

    }

}
