<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request): Response|Application|ResponseFactory
    {
        try {
            $user = User::create(array_merge(
                $request->validated(),
                ['password' => Hash::make($request->input('password'))]
            ));

            return response([
                'message' => 'ok',
                'data' => $user,
            ], 200);
        } catch (Exception $exception) {
            return response([
                'message' => 'error',
                'data' => $exception->getMessage(),
            ], 500);
        }
    }
}
