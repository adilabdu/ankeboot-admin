<?php

namespace App\Http\Controllers;

use App\Mail\SubscriberWelcome;
use App\Models\MailingList;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Mail;

class MailingListController extends Controller
{

    public function index(Request $request)
    {

        $request->validate([
            'id' => 'nullable|exists:mailing_list,id',
        ]);

        try {

            $mailingList = MailingList::query()
                ->when($request->input('id'), function ($query, $id) {
                    return $query->where('id', $id)->first();
                }, function ($query) {
                    return $query->orderBy('created_at', 'desc')->get();
                });

        } catch (Exception $e) {

            return response([
                'message' => 'error',
                'data' => $e->getMessage(),
            ], 500);

        }

        return response([
            'message' => 'success',
            'data' => $mailingList,
        ], 200);

    }

    public function delete(Request $request)
    {

        $request->validate([
            'id' => 'required|exists:mailing_list,id',
        ]);

        try {

            $mailingList = MailingList::query()
                ->where('id', $request->input('id'))
                ->delete();

        } catch (Exception $e) {

            return response([
                'message' => 'error',
                'data' => $e->getMessage(),
            ], 500);

        }

        return response([
            'message' => 'success',
            'data' => $mailingList,
        ], 200);

    }

    public function post(Request $request): Response|Application|ResponseFactory
    {

        $request->validate([
            'name' => 'required|string',
            'email' => 'required_without:phone|email',
            'phone' => 'required_without:email|numeric',
        ]);

        try {

                $mailingList = MailingList::create($request->all());

            } catch (Exception $e) {

                return response([
                    'message' => 'error',
                    'data' => $e->getMessage(),
                ], 500);

        }

        if ($mailingList->email && Env::get('MAIL_ENABLE')) {
            Mail::to($mailingList->email)->send(new SubscriberWelcome($mailingList));
        }

        return response([
            'message' => 'success',
            'data' => $mailingList,
        ], 200);

    }

}
