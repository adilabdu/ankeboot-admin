<?php

namespace App\Http\Controllers;

use App\Enums\DepositType;
use App\Http\Requests\UpdateDailySaleRequest;
use App\Models\DailySale;
use App\Models\Deposit;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class DailySaleController extends Controller
{

    public function index(Request $request): Response|Application|ResponseFactory
    {

        $request->validate([
            'limit' => 'integer|required_with:page|prohibits:id',
            'page' => 'integer|required_with:limit',
            'id' => 'integer|exists:daily_sales,id'
        ]);

        try {

            if ($request->has('limit')) {

                $dailySales = DailySale::latest('date_of')
                    ->skip(($request->input('page') - 1) * $request->input('limit'))
                    ->take($request->input('limit'))
                    ->get();

            } else if ($request->has('id')) {

                $dailySales = DailySale::with('expenses')
                    ->with('sales_receipts')
                    ->with('deposits')
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
            'July 20, 2022'
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

            $collected = 0.00;
            $reported = 0.00;

            $dailySale = DailySale::find($request->input('id'));
            $dailySale->update([
                'credit_sales' => $request->input('credit_sales') ?? 0.00,
                'cashier' => $request->input('cashier')
            ]);

            $dailySale->deposits()->createMany($request->input('deposits'));
            $dailySale->sales_receipts()->createMany($request->input('sales_receipts'));
            $dailySale->expenses()->createMany($request->input('expenses'));

            foreach ($request->input('deposits') as $deposit) {
                $collected += $deposit['amount'];
            }

            foreach ($request->input('expenses') as $expense) {
                $collected += $expense['amount'];
            }

            foreach ($request->input('sales_receipts') as $sales_receipt) {
                $reported += $sales_receipt['amount'];
            }

            $reported += $request->input('credit_sales') ?? 0.00;

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
