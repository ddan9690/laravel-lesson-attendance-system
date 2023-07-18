<?php

namespace App\Http\Requests\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }

    /**
     * Authenticate the user's credentials.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $credentials = $this->only('email', 'password');
        $remember = $this->filled('remember'); // Check if the "remember" field is filled

        if (!Auth::attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
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
