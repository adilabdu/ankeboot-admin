<?php

namespace App\Http\Controllers;

use App\Models\MailingList;
use Illuminate\Http\Request;
use Exception;

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

}
