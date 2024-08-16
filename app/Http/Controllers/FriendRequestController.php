<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FriendRequest;

class FriendRequestController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $sentRequests = $user->sentFriendRequests->map(function($request) {
            return array_merge($request->toArray(), [
                'userData' => $request->recipient
            ]);
        });

        $receivedRequests = $user->receivedFriendRequests->map(function($request) {
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

        if ($request->status === 'declined') {
            $friendRequest->delete();
            return response()->json(['message' => 'Friend request declined and deleted successfully.']);
        } else {
            $friendRequest->update([
                'status' => $request->status,
            ]);
            return response()->json(['message' => 'Friend request updated successfully.', 'friend_request' => $friendRequest]);
        }
    }

    public function destroy($id)
    {
        $friendRequest = FriendRequest::findOrFail($id);
        $friendRequest->delete();

        return response()->json(['message' => 'Friend request deleted successfully.']);
    }
}
