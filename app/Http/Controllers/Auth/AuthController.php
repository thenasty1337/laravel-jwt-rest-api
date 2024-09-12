<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Users\CreateUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Users\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request, CreateUser $createUser): JsonResponse
    {
        // Use the CreateUser action to create the user
        $user = $createUser(
            name: $request->input('name'),
            email: $request->input('email'),
            password: $request->input('password'), // Ensure the password is hashed
        );

       $credentials = $request->only(['email', 'password']);

        $token = Auth::attempt($credentials);

        if (! $token) {
            return response()->json([
                'status' => 'invalid-credentials',
            ], 401);
        }


        // Return the token in the response
        return response()->json([
            'status' => 'user-created',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);

        $token = Auth::attempt($credentials);

        if (! $token) {
            return response()->json([
                'status' => 'invalid-credentials',
            ], 401);
        }

        return response()->json([
            'user'         => new UserResource(Auth::user()),
            'access_token' => $token,
        ]);
    }

    public function logout(): Response
    {
        Auth::logout();

        return response()->noContent();
    }

    public function refresh(): JsonResponse
    {
        $token = Auth::refresh();

        return response()->json([
            'access_token' => $token,
        ]);
    }
}
