<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function registerUser(array $request): JsonResponse
    {
        $validatedData = $request;

        $user = User::create([
            'login' => $validatedData['login'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        $credentials = [
            'login' => $user->login,
            'password' => $validatedData['password'],
        ];

        return $this->loginUser($credentials);
    }
    public function prepareCredentials(array $data): array
    {
        $identifier = $data['identifier'];
        $password = $data['password'];

        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            return ['email' => $identifier, 'password' => $password];
        } else {
            return ['login' => $identifier, 'password' => $password];
        }
    }


    public function loginUser(array $credentials): JsonResponse
    {
        if (Auth::attempt($credentials)) {
            $token = Auth::user()->createToken('authToken')->plainTextToken;

            return response()->json([
                'user' => Auth::user(),
                'token' => $token
            ], 200);
        } else {
            return response()->json([
                'error' => 'Invalid credentials',
            ], 401);
        }
    }
}
