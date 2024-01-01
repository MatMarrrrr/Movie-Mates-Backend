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
        $credentials = $authService->prepareCredentials($request->all());
        return $authService->loginUser($credentials);
    }
}
