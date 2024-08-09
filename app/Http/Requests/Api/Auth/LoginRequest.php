<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\IsApiRequest;

class LoginRequest extends FormRequest
{
    use IsApiRequest;

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email is required!',
            'email.email' => 'Email is invalid!',
            'password.required' => 'Password is required!',
        ];
    }
}
