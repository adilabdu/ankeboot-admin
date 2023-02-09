<?php

namespace App\Http\Controllers;

use App\Events\ReminderReached;
use App\Jobs\DelayReminder;
use App\Models\Reminder;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ReminderController extends Controller
{

    public function post(Request $request): Response|Application|ResponseFactory
    {

        $request->validate([
            'description' => 'required',
            'priority' => 'string',
            'notify_at' => 'date_format:d-m-Y H:i|after:now'
        ]);

        try {

            $reminder = Reminder::create([
                'description' => $request->input('description'),
                'priority' => $request->input('priority'),
                'user_id' => Auth::user()->id
            ]);

            DelayReminder::dispatch($reminder)
                ->delay(new Carbon($request->input('notify_at')));

        } catch (Exception $exception) {

            return response([
                'message' => 'error',
                'data' => $exception->getMessage()
            ], 500);

        }

        return response([
            'message' => 'success',
            'data' => $reminder
        ]);

    }

}
