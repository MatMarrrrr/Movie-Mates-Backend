<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ImgurController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['image' => 'required|file']);

        $image = $request->file('image');
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('IMGUR_BEARER_TOKEN')
        ])->post('https://api.imgur.com/3/image', [
            'image' => base64_encode(file_get_contents($image->getRealPath()))
        ]);

        if ($response->successful()) {
            return response()->json(['link' => $response->json()['data']['link']]);
        } else {
            return response()->json(['error' => 'Failed to upload image'], 500);
        }
    }
}
