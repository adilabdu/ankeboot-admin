<?php

namespace App\Jobs;

use App\Models\Book;
use App\Models\User;
use App\Notifications\QueueJobFinished;
use Carbon\Carbon;
use \Illuminate\Support\Facades\Log as LaravelLog;
use App\Traits\ReadsCSV;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\UploadedFile;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;

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
        try {

            $now = Carbon::now();

            DB::beginTransaction();

            foreach ($this->records as $i => $record) {

                LaravelLog::info($record);

                Validator::validate($record, [
                    'code' => 'unique:books,code'
                ], [
                    'code.unique' => 'The code at #'. $i + 1 . ' has already been taken'
                ]);

                Book::create(array_merge($record, [
                    'balance' => 0
                ]));

            }

            $time_elapsed = $now->diffInSeconds(Carbon::now());

            Notification::send($this->user, new QueueJobFinished([
                'title' => 'Register ',
                'status' => 'success',
                'time' => $time_elapsed
            ]));

            DB::commit();

        } catch (Exception $e) {

            DB::rollBack();

            LaravelLog::info("Error while processing file...{$e}");

        }
    }
}
