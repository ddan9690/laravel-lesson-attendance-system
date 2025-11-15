<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\View\View;

class AuthenticatedSessionController
{
    /**
     * Show the login form.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle login submission.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Authenticate user and get role-based redirect
        $redirectRoute = $request->authenticate();

        // Regenerate session
        $request->session()->regenerate();

        return redirect()->intended($redirectRoute);
    }

    /**
     * Logout user.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Clear remember-me cookie
        Cookie::queue(Cookie::forget('remember_me'));

        return redirect('/');
    }
}
