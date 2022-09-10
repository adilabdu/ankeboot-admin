<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class NotificationController extends Controller
{

    public function index()
    {

        try {
                
            $notifications = Auth::user()->notifications;

        } catch (Exception $e) {

            return response([
                'message' => 'error',
                'data' => $e->getMessage(),
            ], 500);
    
        }
        
        return response([
            'message' => 'success',
            'data' => $notifications,
        ], 200);

    }
    
    public function unread()
    {

        try {

            $unread = Auth::user()->unreadNotifications;

        } catch(Exception $e) {

            return response([
                'message' => 'error',
                'data' => $e->getMessage()
            ], 500);

        }

        return response([
            'message' => 'success',
            'data' => $unread
        ], 200);

    }

    public function read(Request $request)
    {

        $request->validate([
            'id' => 'required|exists:notifications,id'
        ]);

        try {

            Auth::user()
                ->notifications
                ->where('id', $request->id)
                ->first()
                ->markAsRead();

        } catch (Exception $e) {

            return response([
                'message' => 'error',
                'data' => $e->getMessage()
            ], 500);

        }

        return response([
            'message' => 'success',
            'data' => 'Notification marked as read'
        ], 200);

    }

}
