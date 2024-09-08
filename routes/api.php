<?php

use App\Http\Controllers\CommentsController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\MovieController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FriendRequestController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\ImgurController;
use App\Http\Controllers\UserController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('auth/google', [GoogleAuthController::class, 'redirect']);
Route::get('auth/google/callback', [GoogleAuthController::class, 'callbackGoogle']);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'getUser']);
        Route::get('/all', [UserController::class, 'getAllUsers']);
        Route::get('/search', [UserController::class, 'searchUsers']);
        Route::patch('/login', [UserController::class, 'updateLogin']);
        Route::patch("/email", [UserController::class, 'updateEmail']);
        Route::patch("/password", [UserController::class, 'changePassword']);
        Route::patch('/avatar', [UserController::class, 'updateAvatar']);
        Route::get('/friends', [UserController::class, 'getFriends']);
    });

    Route::post('/imgur/upload-image', [ImgurController::class, 'store']);

    Route::get('/movie/{id}', [MovieController::class, 'getMovieDetails']);

    Route::prefix('movies')->group(function () {
        Route::get('/trending/{period}/{page?}', [MovieController::class, 'getTrendingMovies']);
        Route::get('/{movieId}/comments', [CommentsController::class, 'getMovieComments']);
        Route::post('/{movieId}/comments', [CommentsController::class, 'addMovieComment']);
    });

    Route::delete('/friends/{friendId}/unfriend', [FriendController::class, 'unfriend']);

    Route::resource('friend-requests', FriendRequestController::class);
});
