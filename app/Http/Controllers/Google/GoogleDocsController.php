<?php

namespace App\Http\Controllers\Google;

use App\Http\Controllers\Controller;
use Google\Service\Drive;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class GoogleDocsController extends GoogleController
{
    public function getFiles(): Response|Application|ResponseFactory
    {

        $client = parent::getUserClient();

        $service = new Drive($client);
        $results = $service->files->listFiles([
            'pageSize' => 10,
        ]);

        return response([
            'data' => $results
        ], 200);
    }
}
