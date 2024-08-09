<?php

use Illuminate\Support\Facades\Route;

Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login'])->name('login');


// Authenticated
Route::group([
    'middleware' => ['auth:api'],
], function() {
    Route::get('/me', [\App\Http\Controllers\Api\AuthController::class, 'me'])->name('me');
    Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout'])->name('logout');
});

