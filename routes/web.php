<?php

use App\Http\Controllers\SecretFriendController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/secret-friend/{game_uuid}/{player:uuid}', [SecretFriendController::class, 'show'])
    ->name('secret-friend.show');
