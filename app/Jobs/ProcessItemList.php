<?php

namespace App\Jobs;

use App\Models\Book;
use App\Models\User;
use App\Notifications\QueueJobFinished;
use App\Traits\ReadsCSV;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log as LaravelLog;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class ProcessItemList implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ReadsCSV;

    public array $records;

    public User $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $request, User $user)
    {
        $this->records = $request;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $now = Carbon::now();

        try {
            DB::beginTransaction();

            foreach ($this->records as $i => $record) {
                LaravelLog::info($record);

                Validator::validate($record, [
                    'code' => 'unique:books,code',
                ], [
                    'code.unique' => 'The code at #'. $i + 1 .' has already been taken',
                ]);

                $item = Book::make($record);
                $item->balance = 0;
                $item->save();
            }

            Notification::send($this->user, new QueueJobFinished([
                'description' => 'You have successfully added '.count($this->records).' new book items into your records',
                'status' => 'success',
                'time' => $now->diffInMicroseconds(Carbon::now()),
            ]));

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            LaravelLog::info("Error while processing file...{$e}");

            Notification::send($this->user, new QueueJobFinished([
                'description' => 'An error occurred while inserting your book items.',
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                ],
                'status' => 'error',
                'time' => $now->diffInMicroseconds(Carbon::now()),
            ]));
        }
    }
}
