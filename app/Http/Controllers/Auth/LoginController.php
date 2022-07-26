<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        try {

            $request->authenticate();

        } catch (ValidationException $e) {

            return response()
                ->json([
                    'message' => 'error',
                    'data' => $e->getMessage()
                ], 500);

        }

        // Create token for user
        $token = $request->user()->createToken(auth()->user()->name);

        return response()
            ->json([
                'message' => 'ok',
                'data' => [
                    'token' => $token->plainTextToken,
                    'user' => auth()->user()
                ],
            ], 200);
    }
}
