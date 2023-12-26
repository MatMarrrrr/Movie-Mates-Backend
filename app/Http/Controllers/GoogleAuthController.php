<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function callbackGoogle()
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

            return redirect('http://localhost:5173/google-callback?token=' . $token);

        } catch (\Throwable $th) {
            return redirect('http://localhost:5173/error');
        }
    }

}
