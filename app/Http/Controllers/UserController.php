<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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

        return response()->json([
            'success' => true,
            'message' => 'Login updated successfully.'
        ]);
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users'
        ]);

        $user = Auth::user();

        $user->email = $request->input('email');
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Email updated successfully.'
        ]);
    }


    public function changePassword(Request $request)
    {
        $request->validate([
            'oldPassword' => 'required|string',
            'newPassword' => 'required|string|min:8',
            'passwordRepeat' => 'required|string|min:8',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->input('oldPassword'), $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Old password is incorrect.',
            ], 400);
        }

        if (Hash::check($request->input('newPassword'), $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'New password must be different from the old password.',
            ], 400);
        }

        if ($request->input('newPassword') !== $request->input('passwordRepeat')) {
            return response()->json([
                'success' => false,
                'message' => 'New password and repeated password do not match.',
            ], 400);
        }

        $user->password = Hash::make($request->input('newPassword'));
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully.',
        ]);
    }
}
