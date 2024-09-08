<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use Illuminate\Http\Request;

class FriendController extends Controller
{
    public function unfriend($friendId)
    {
        $loggedUserId = auth()->id();

        $friendship = Friend::where(function ($query) use ($loggedUserId, $friendId) {
            $query->where('friend1_id', $loggedUserId)
                ->where('friend2_id', $friendId);
        })->orWhere(function ($query) use ($loggedUserId, $friendId) {
            $query->where('friend1_id', $friendId)
                ->where('friend2_id', $loggedUserId);
        })->first();

        if ($friendship) {
            $friendship->status = 'inactive';
            $friendship->save();

            return response()->json(['message' => 'Friendship has been deactivated.'], 200);
        }

        return response()->json(['message' => 'Friendship not found.'], 404);
    }
}
