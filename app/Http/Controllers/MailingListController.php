<?php

namespace App\Http\Controllers;

use App\Models\MailingList;
use App\Models\User;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewSubscriberRegistered;

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
                    return $query->get();
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

    public function post(Request $request)
    {

        $request->validate([
            'name' => 'required|string',
            'email' => 'required_without:phone|email',
            'phone' => 'required_without:email|numeric',
        ]);

        try {
                
                $mailingList = MailingList::create($request->all());

                Notification::send(User::all(), new NewSubscriberRegistered($mailingList));
            
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

}
