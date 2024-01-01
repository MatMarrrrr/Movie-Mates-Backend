<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    public function getAllUsers()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function getUser(Request $request)
    {
        return $request->user();
    }
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar_url' => 'required|url'
        ]);

        $user = Auth::user();

        $user->avatar_url = $request->input('avatar_url');
        $user->save();

        return response()->json(['message' => 'Avatar updated successfully.']);
    }

    public function updateLogin(Request $request)
    {
        $request->validate([
            'login' => 'required|string|min:3|max:255|unique:users|not_regex:/@/'
        ]);

        $user = Auth::user();

        $user->login = $request->input('login');
        $user->save();

        return response()->json(['message' => 'Login updated successfully.']);
    }
}
