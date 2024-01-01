<?php

namespace App\Services;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleAuthService
{
    public function handleGoogleCallback()
    {
        try {
            $google_user = Socialite::driver('google')->stateless()->user();
            $user = User::where('google_id', $google_user->getId())->first();

            if (!$user) {
                $user = User::create([
                    'login' => $google_user->getName(),
                    'email' => $google_user->getEmail(),
                    'google_id' => $google_user->getId(),
                    'avatar_url' => $google_user->getAvatar(),
                    'account_type' => '1',
                ]);
            }

            Auth::login($user);

            $token = $user->createToken('authToken')->plainTextToken;

            return ['redirect' => 'http://localhost:5173/google-callback?token=' . $token];

        } catch (\Throwable $th) {
            return ['error' => true, 'redirect' => 'http://localhost:5173/error'];
        }
    }
}
