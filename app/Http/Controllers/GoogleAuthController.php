<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Services\GoogleAuthService;
class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function callbackGoogle(GoogleAuthService $googleAuthService)
    {
        $response = $googleAuthService->handleGoogleCallback();
        return redirect($response['redirect']);
    }

}
