<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Deposit;
use App\Models\DailySale;
use App\Models\DailySaleExpense as Expense;
use App\Models\SalesReceipt;
use App\Models\Credit;
use App\Enums\DepositType;
use Exception;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {

        $this->seedUser();
        $this->seedDailySales();

    }

    private function seedUser()
    {
        try {

            DB::table('users')->insertOrIgnore([
                [
                    'name' => 'Test User',
                    'email' => 'testuser@test.com',
                    'username' => 'testuser',
                    'phone_number' => '0912345678',
                    'password' => Hash::make('helloworld'),
                    'telegram_chat_id' => '5783481231'
                ],
                [
                    'name' => 'Adil Abdu Bushra',
                    'email' => 'adil@ankeboot.com',
                    'username' => 'adil',
                    'phone_number' => '0912272145',
                    'password' => Hash::make('helloworld'),
                    'telegram_chat_id' => '340546535'
                ]
            ]);

        } catch (Exception $e) {
            dd('Error: ' . $e->getMessage());
        }
    }

    private function seedDailySales()
    {

        // TODO: find all `unsubmitted` daily sales
        // For each daily sale, update its record
        // as such:
        // `is_submitted` = true
        // `cashier` = 'Elsa'


        // Random data to generate
        // 1. # of sales receipts (1 or 2)
        // 2. amount of sales receipts (1500 < x < 20000)

        try {

            DB::beginTransaction();

            foreach (DailySale::all() as $dailySale) {

                if (Carbon::createFromDate($dailySale->date_of) == Carbon::yesterday()) {
                    continue;
                }

                // randomly set min difference between `collected` and `reported`
                $difference = rand(-5, 5);
                $collected = 0.00;
                $reported = 0.00;

                // update daily sale attribute
                $dailySale->update([
                    'cashier' => 'Elsa'
                ]);

                // generate random number of sales receipts
                $salesReceipts = [];
                for ($i = 0; $i < rand(1, 2); $i++) {

                    $salesReceipts[] = [
                        'receipt' => 'Z:' . rand(10000, 99999),
                        'amount' => rand(1500, 20000) + (rand(0, 99) / 100),
                        'is_manual' => rand(0, 1) === 1,
                    ];

                }
                $dailySale->sales_receipts()->createMany($salesReceipts);

                // get total amount of sales receipts
                $salesReceiptTotalAmount = 0.00;
                foreach ($salesReceipts as $sales) {
                    $salesReceiptTotalAmount += $sales['amount'];
                }

                // generate a single deposit
                $deposit = [
                    'amount' => rand(0, intval($salesReceiptTotalAmount)) + (rand(0, 99) / 100),
                    'type' => DepositType::CASH,
                    'deposited_on' => $dailySale['date_of']->addDay(),
                ];
                $dailySale->deposits()
                    ->save(new Deposit($deposit));

                // update total amount of sales receipts
                $salesReceiptTotalAmount -= $deposit['amount'];

                // randomly generate an additional deposit
                if (rand(0, 1000) % 5 === 0) {
                    $deposit = [
                        'amount' => rand(0, intval($salesReceiptTotalAmount)) + (rand(0, 99) / 100),
                        'type' => [
                                DepositType::CASH,
                                DepositType::CARD,
                                DepositType::CHEQUE,
                                DepositType::WITHHOLDING,
                                DepositType::TRANSFER,
                            ][rand(0, 4)],
                        'deposited_on' => $dailySale['date_of'],
                    ];
                    $dailySale->deposits()
                        ->save(new Deposit($deposit));

                    // update total amount of sales receipts
                    $salesReceiptTotalAmount -= $deposit['amount'];

                }

                // generate random number of credit sales
                if ($salesReceiptTotalAmount >= 500 && (rand(0, 1000) % 7) === 0) {

                    $deposit = [
                        'amount' => rand(500, intval($salesReceiptTotalAmount)) + (rand(0, 99) / 100),
                        'type' => DepositType::CREDIT,
                        'deposited_on' => $dailySale['date_of'],
                    ];

                    $dailySale->deposits()
                        ->save(new Deposit($deposit))
                        ->credit()->save(new Credit([
                            'receipt' => 'FS' . rand(10000, 99999),
                            'client_name' => fake()->company(),
                        ]));

                    // update total amount of sales receipts
                    $salesReceiptTotalAmount -= $deposit['amount'];

                }

                // generate random number of expenses
                if (rand(0, 1000) % 4 === 0) {

                        $expense = [
                            'receipt' => 'PV' . rand(10000, 99999),
                            'amount' => rand(0, intval($salesReceiptTotalAmount)) + (rand(0, 99) / 100),
                            'description' => 'Consignment payment #' . rand(1, 4) . ' for ' . fake()->name(),
                        ];

                        $dailySale->expenses()
                            ->save(new Expense($expense));

                        // update total amount of sales receipts
                        $salesReceiptTotalAmount -= $expense['amount'];

                }

                // generate an additional deposit to cover the difference
                $deposit = [
                    'amount' => $salesReceiptTotalAmount + ($difference +  rand(0, 99) / 100),
                    'type' => DepositType::CASH,
                    'deposited_on' => $dailySale['date_of'],
                ];
                $dailySale->deposits()
                    ->save(new Deposit($deposit));

                // update total amount of sales receipts
                $salesReceiptTotalAmount -= $deposit['amount'];

                $dailySale->is_submitted = true;
                $dailySale->difference = round($salesReceiptTotalAmount, 2) * -1;
                $dailySale->save();

            }

            DB::commit();

        } catch (Exception $e) {

            DB::rollBack();
            dd('Error: ' . $e->getMessage());

        }

    }

}
