<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\JsonResponse;
use App\Services\AuthService;

class AuthController extends Controller
{
    public function register(RegisterRequest  $request, AuthService $authService): JsonResponse
    {
        return $authService->registerUser($request->validated());
    }

    public function login(LoginRequest  $request, AuthService $authService): JsonResponse
    {
        $identifier = $request->get('identifier');
        $password = $request->get('password');

        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $credentials = ['email' => $identifier, 'password' => $password];
        } else {
            $credentials = ['login' => $identifier, 'password' => $password];
        }
        return $authService->loginUser($credentials);
    }
}
