<?php

namespace App\Services;

use App\Models\User;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\JsonResponse;

class AuthService
{
   public function registerUser(RegisterRequest  $request): JsonResponse
   {
       $validatedData = $request->validated();

       $user = User::create([
           'login' => $validatedData['login'],
           'email' => $validatedData['email'],
           'password' => bcrypt($validatedData['password']),
       ]);

       $token = $user->createToken('authToken')->plainTextToken;

       return response()->json([
           'user' => $user,
           'token' => $token
       ]);
   }
}
