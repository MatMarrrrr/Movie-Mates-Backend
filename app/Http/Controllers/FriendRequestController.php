<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FriendRequest;
use App\Models\Friend;

class FriendRequestController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $status = $request->input('status');

        $sentRequestsQuery = $user->sentFriendRequests();
        if ($status) {
            $sentRequestsQuery->where('status', $status);
        }
        $sentRequests = $sentRequestsQuery->get()->map(function ($request) {
            return array_merge($request->toArray(), [
                'userData' => $request->recipient
            ]);
        });

        $receivedRequestsQuery = $user->receivedFriendRequests();
        if ($status) {
            $receivedRequestsQuery->where('status', $status);
        }
        $receivedRequests = $receivedRequestsQuery->get()->map(function ($request) {
            return array_merge($request->toArray(), [
                'userData' => $request->sender
            ]);
        });

        return response()->json([
            'sent_requests' => $sentRequests,
            'received_requests' => $receivedRequests
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,id',
        ]);

        $user = auth()->user();

        $existingRequest = FriendRequest::where(function ($query) use ($user, $request) {
            $query->where('sender_id', $user->id)
                ->where('recipient_id', $request->recipient_id);
        })->orWhere(function ($query) use ($user, $request) {
            $query->where('sender_id', $request->recipient_id)
                ->where('recipient_id', $user->id);
        })->first();

        if ($existingRequest && $existingRequest->status === 'pending') {
            return response()->json(['message' => 'There is already a pending friend request between these users.'], 400);
        }

        $friendRequest = $user->sentFriendRequests()->create([
            'recipient_id' => $request->recipient_id,
        ]);

        return response()->json(['message' => 'Friend request sent successfully.', 'friend_request' => $friendRequest], 201);
    }

    public function show($id)
    {
        $friendRequest = FriendRequest::findOrFail($id);

        return response()->json($friendRequest);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:accepted,declined',
        ]);

        $friendRequest = FriendRequest::findOrFail($id);

        if ($request->status === 'accepted') {
            $existingFriend = Friend::where(function ($query) use ($friendRequest) {
                $query->where('friend1_id', $friendRequest->sender_id)
                    ->where('friend2_id', $friendRequest->recipient_id);
            })->orWhere(function ($query) use ($friendRequest) {
                $query->where('friend1_id', $friendRequest->recipient_id)
                    ->where('friend2_id', $friendRequest->sender_id);
            })->first();

            if ($existingFriend) {
                $existingFriend->update(['status' => 'active']);
            } else {
                Friend::create([
                    'friend1_id' => $friendRequest->sender_id,
                    'friend2_id' => $friendRequest->recipient_id,
                    'status' => 'active',
                ]);
            }
        }

        $friendRequest->update([
            'status' => $request->status,
        ]);

        return response()->json(['message' => 'Friend request updated successfully.', 'friend_request' => $friendRequest]);

    }

    public function destroy($id)
    {
        $friendRequest = FriendRequest::findOrFail($id);
        $friendRequest->delete();

        return response()->json(['message' => 'Friend request deleted successfully.']);
    }
}
