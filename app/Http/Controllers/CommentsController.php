<?php

namespace App\Http\Controllers;

use App\Models\MovieComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    public function getMovieComments($movieId)
    {
        $comments = MovieComment::where('movie_id', $movieId)->with('user')->get();
        return response()->json($comments);
    }

    public function addMovieComment(Request $request, $movieId)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $user = Auth::user();
        $comment = $user->movieComments()->create([
            'movie_id' => $movieId,
            'content' => $request->content,
        ]);

        return response()->json($comment, 201);
    }
}
