<?php

use Illuminate\Support\Facades\Route;

// Login
Route::post('/login', [App\Http\Controllers\Api\V1\Auth\AuthenticatedController::class, 'login'])->name('login');

// Must be authenticated
Route::middleware('auth:api')->group(function () {
    // Me
    Route::get('/me', [App\Http\Controllers\Api\V1\Auth\AuthenticatedController::class, 'me'])->name('me');

    // Logout
    Route::post('/logout', [App\Http\Controllers\Api\V1\Auth\AuthenticatedController::class, 'logout'])->name('logout');
});
