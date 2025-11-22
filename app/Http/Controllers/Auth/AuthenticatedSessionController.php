<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
     * Handle login submission - FIXED role matching
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();
        $roles = $user->getRoleNames();

        // DEBUG: Remove this after fixing
        \Log::info('Login Debug', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'roles' => $roles->toArray(),
            'first_role' => $roles->first()
        ]);

        // FIXED: Exact role matching with proper fallbacks
        if ($roles->contains('super_admin')) {
            return redirect()->route('dashboard.admin');
        }

        if ($roles->contains('principal')) {
            return redirect()->route('dashboard.admin');
        }

        if ($roles->contains('deputy')) {
            return redirect()->route('dashboard.admin');
        }

        if ($roles->contains('dos')) {
            return redirect()->route('dashboard.admin');
        }

        if ($roles->contains('senior_teacher')) {
            return redirect()->route('dashboard.admin');
        }

        if ($roles->contains('committee_member')) {
            return redirect()->route('dashboard.committee');
        }

        if ($roles->contains('class_supervisor')) {
            return redirect()->route('dashboard.class_supervisor');
        }

        if ($roles->contains('class_teacher')) {
            return redirect()->route('dashboard.class_teacher');
        }

        if ($roles->contains('teacher')) {
            return redirect()->route('dashboard.teacher');
        }

        // Ultimate fallback
        return redirect()->route('dashboard.admin');
    }

    /**
     * Logout user.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}