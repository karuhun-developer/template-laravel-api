<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        // If invalid
        if (!Auth::attempt($credentials)) {
            return $this->respondWithError('Your credentials are incorrect. Please try again.');
        }

        // Save activity
        activity()->performedOn(auth()->user())->causedBy(auth()->user())->log('Login');

        // Token expiration
        $expiration = now()->addWeek();

        $token = Auth::user()->createToken('authToken', ['*'], $expiration)->plainTextToken;

        return $this->respondWithSuccess([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $expiration->timestamp,
        ]);
    }

    public function me() {
        return $this->respondWithSuccess(auth()->user());
    }

    public function logout(Request $request)
    {
        // Save activity
        activity()->performedOn(auth()->user())->causedBy(auth()->user())->log('Logout');

        // Delete token
        $request->user()->currentAccessToken()->delete();

        return $this->respondWithSuccess('Successfully logged out.');
    }
}
