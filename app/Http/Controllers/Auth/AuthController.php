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
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(RegisterRequest $request, CreateUser $createUser): JsonResponse
    {
        try {
            // Use the CreateUser action to create the user
            $user = $createUser(
                name: strip_tags($request->input('name') ?? $request->input('username')),
                username: strip_tags($request->input('username')),
                email: filter_var($request->input('email'), FILTER_SANITIZE_EMAIL),
                password: $request->input('password'),
                avatar: filter_var($request->input('avatar'), FILTER_SANITIZE_URL)
            );

            $credentials = $request->only(['email', 'password']);
            $token = Auth::attempt($credentials);
            $refreshToken = Auth::refresh();

            if (!$token) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid credentials after registration',
                ], 401);
            }

            // Return success response with token
            return response()->json([
                'status' => 'success',
                'message' => 'User registered successfully',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'refresh_token' => $refreshToken,

                'user' => new UserResource(Auth::user()),
            ], 201);
        } catch (\Exception $e) {
            // Log the error for debugging, but don't expose it to the user
            \Log::error('Registration failed: ' . $e->getMessage());

            // Return a generic error message
            return response()->json([
                'status' => 'error',
                'message' => 'Registration failed. Please try again later.',
            ], 500);
        }
    }


    /**
     * Log in an existing user
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $credentials = $request->only(['email', 'password']);
            $token = Auth::attempt($credentials);
            $refreshToken = Auth::refresh();
            if (!$token) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid credentials',
                ], 401);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Login successful',
                'user' => new UserResource(Auth::user()),
                'access_token' => $token,
                'token_type' => 'Bearer',
                'refresh_token' => $refreshToken,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Login failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Log out the currently authenticated user
     */
    public function logout(): JsonResponse
    {
        try {
            Auth::logout();
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully logged out',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Logout failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Refresh the authentication token
     */
    public function refresh(): JsonResponse
    {
        try {
            $token = Auth::refresh();
            return response()->json([
                'status' => 'success',
                'message' => 'Token refreshed successfully',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => new UserResource(Auth::user()),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token refresh failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Check if the email is already in use
     */
    public function checkEmail(Request $request): JsonResponse
    {
        $email = $request->input('email');
        $user = User::where('email', $email)->first();
        return response()->json(['exists' => $user !== null]);
    }

    /**
     * Get the currently authenticated user
     */
    public function me(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'user' => new UserResource(Auth::user()),
        ]);
    }
}
