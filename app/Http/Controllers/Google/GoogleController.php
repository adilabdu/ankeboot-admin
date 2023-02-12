<?php

namespace App\Http\Controllers\Google;

use App\Http\Controllers\Controller;
use Google\Exception;
use Google\Service\Docs;
use Google_Client;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{
    /**
     * @throws Exception
     */
    protected function getClient(): Google_Client
    {

        $client = new Google_Client();
        $client->setApplicationName('Ankeboot');
        $client->setAuthConfig(config('google'));
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');

        $client->setScopes([
            Docs::DRIVE,
            Docs::DOCUMENTS,
        ]);

        $client->setIncludeGrantedScopes(true);


        return $client;
    }

    protected function getUserClient(): Response|Google_Client|Application|ResponseFactory
    {
        try {
            $accessToken = stripslashes(Auth::user()->google_access_token);
            $client = $this->getClient();
            $client->setAccessToken($accessToken);

            if ($client->isAccessTokenExpired()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                $client->setAccessToken($client->getAccessToken());

                Auth::user()->google_access_token = json_encode($client->getAccessToken());
                Auth::user()->save();
            }

        } catch (Exception $exception) {

            return response([
                'message' => 'error',
                'data' => $exception->getMessage()
            ], 500);

        }

        return $client;
    }
}
