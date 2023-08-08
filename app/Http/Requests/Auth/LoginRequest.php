<?php

namespace App\Http\Requests\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Foundation\Http\FormRequest;
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

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
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

        if ($remember) {
            $this->setRememberMeCookie();
        } else {
            $this->deleteRememberMeCookie();
        }
    }

    private function setRememberMeCookie(): void
    {
        $rememberToken = Auth::user()->getRememberToken();
        Cookie::queue('remember_me', $rememberToken, 43200); // Remember Me cookie for 30 days
    }

    private function deleteRememberMeCookie(): void
    {
        Cookie::queue(Cookie::forget('remember_me'));
    }
}
