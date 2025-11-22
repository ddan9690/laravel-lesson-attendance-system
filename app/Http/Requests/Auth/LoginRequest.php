<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    public function authenticate(): void
    {
        $credentials = $this->only('login', 'password');
        $remember = $this->filled('remember');

        $fieldType = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        if (!Auth::attempt([$fieldType => $credentials['login'], 'password' => $credentials['password']], $remember)) {
            throw ValidationException::withMessages([
                'login' => __('auth.failed'),
            ])->redirectTo($this->getRedirectUrl());
        }

        // Remember-me cookie
        $user = Auth::user();

        if ($remember) {
            $rememberToken = $user->getRememberToken();
            Cookie::queue('remember_me', $rememberToken, 43200);
        } else {
            Cookie::queue(Cookie::forget('remember_me'));
        }
    }
}
