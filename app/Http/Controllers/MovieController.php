<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class MovieController extends Controller
{
    public function getMovies($period, $page = 1)
    {
        if (!in_array($period, ['day', 'week'])) {
            return response()->json(['error' => 'Invalid period specified'], 400);
        }

        $apiKey = env('TMDB_API_KEY');
        $apiUrl = "https://api.themoviedb.org/3/trending/movie/$period?api_key=$apiKey&page=$page";

        $response = Http::get($apiUrl);

        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json(['error' => 'Failed to fetch data from TMDb'], 500);
        }
    }

    public function getMovieDetails($id)
    {
        $apiKey = env('TMDB_API_KEY');
        $apiUrl = "https://api.themoviedb.org/3/movie/$id?api_key=$apiKey";

        $response = Http::get($apiUrl);

        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json(['error' => 'Failed to fetch movie details'], 500);
        }
    }
}
