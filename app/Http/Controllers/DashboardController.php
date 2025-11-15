<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController
{
    /**
     * Handle the landing page after login.
     * Redirects user to their role-specific dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // Role-based redirection (priority order)
        if ($user->hasAnyRole(['super_admin', 'principal', 'deputy', 'dos', 'senior_teacher'])) {
            return redirect()->route('dashboard.admin');
        }

        if ($user->hasRole('committee_member')) {
            return redirect()->route('dashboard.committee');
        }

        if ($user->hasRole('class_supervisor')) {
            return redirect()->route('dashboard.class_supervisor');
        }

        if ($user->hasRole('class_teacher')) {
            return redirect()->route('dashboard.class_teacher');
        }

        if ($user->hasRole('teacher')) {
            return redirect()->route('dashboard.teacher');
        }

        // fallback
        return view('home', compact('user'));
    }

    // ========================================
    // DASHBOARD VIEWS
    // ========================================

    public function admin()
    {
        $user = Auth::user();
        return view('dashboards.admin', compact('user'));
    }

    public function committee()
    {
        $user = Auth::user();
        return view('dashboards.committee', compact('user'));
    }

    public function classSupervisor()
    {
        $user = Auth::user();
        return view('dashboards.class_supervisor', compact('user'));
    }

    public function classTeacher()
    {
        $user = Auth::user();
        return view('dashboards.class_teacher', compact('user'));
    }

    public function teacher()
    {
        $user = Auth::user();
        return view('dashboards.teacher', compact('user'));
    }
}
