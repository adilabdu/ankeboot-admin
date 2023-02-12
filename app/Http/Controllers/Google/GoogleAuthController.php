<?php

namespace App\Http\Controllers\Google;

use App\Http\Controllers\Controller;
use Google\Exception;
use Google\Service\Docs;
use Google_Client;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GoogleAuthController extends GoogleController
{
    public function getAuthURL(Request $request): Response|Application|ResponseFactory
    {
        try {
            $client = parent::getClient();
            $auth_url = $client->createAuthUrl();

        } catch (Exception $exception) {

            return response([
                'message' => 'error',
                'data' => $exception->getMessage()
            ], 500);

        }

        return response([
            'auth_url' => $auth_url
        ], 200);
    }

    public function callback(Request $request): Redirector|Application|RedirectResponse
    {

        try {

            $client = parent::getClient();
            $accessToken = $client->fetchAccessTokenWithAuthCode($request->input('code'));
            $client->setAccessToken(json_encode($accessToken));

            Log::info(Auth::user());

            Auth::user()->google_access_token = json_encode($accessToken);
            Auth::user()->save();

        } catch (Exception $exception) {

            return response([
                'message' => 'error',
                'data' => $exception->getMessage()
            ], 500);

        }

        return redirect('/documents');
    }
}
