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

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }

    /**
     * Authenticate the user and return role-based redirect route.
     */
    public function authenticate(): string
    {
        $credentials = $this->only('login', 'password');
        $remember = $this->filled('remember');

        $fieldType = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        if (!Auth::attempt([$fieldType => $credentials['login'], 'password' => $credentials['password']], $remember)) {
            throw ValidationException::withMessages([
                'login' => __('auth.failed'),
            ])->redirectTo($this->getRedirectUrl());
        }

        $user = Auth::user();

        // Handle remember-me cookie
        if ($remember) {
            $this->setRememberMeCookie();
        } else {
            $this->deleteRememberMeCookie();
        }

        // Role-based redirection
        $roleRoutes = [
            'super_admin'     => 'dashboard.admin',
            'principal'       => 'dashboard.admin',
            'deputy'          => 'dashboard.admin',
            'dos'             => 'dashboard.admin',
            'senior_teacher'  => 'dashboard.admin',
            'committee_member'=> 'dashboard.committee',
            'class_supervisor'=> 'dashboard.class_supervisor',
            'class_teacher'   => 'dashboard.class_teacher',
            'teacher'         => 'dashboard.teacher',
        ];

        foreach ($roleRoutes as $role => $route) {
            if ($user->hasRole($role)) {
                return route($route);
            }
        }

        // fallback
        return route('home');
    }

    private function setRememberMeCookie(): void
    {
        $rememberToken = Auth::user()->getRememberToken();
        Cookie::queue('remember_me', $rememberToken, 43200); // 30 days
    }

    private function deleteRememberMeCookie(): void
    {
        Cookie::queue(Cookie::forget('remember_me'));
    }
}
