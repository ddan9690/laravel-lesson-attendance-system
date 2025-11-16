<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController
{
    /**
     * Redirect user to their role-specific dashboard after login.
     */
    public function index()
    {
        $user = Auth::user();

        // Role → Route mapping
        $map = [
            // Admin-level group
            ['roles' => ['super_admin', 'principal', 'deputy', 'dos', 'senior_teacher'], 'route' => 'dashboard.admin'],

            // Committee
            ['roles' => ['committee_member'], 'route' => 'dashboard.committee'],

            // Class supervisors
            ['roles' => ['class_supervisor'], 'route' => 'dashboard.class_supervisor'],

            // Class teachers
            ['roles' => ['class_teacher'], 'route' => 'dashboard.class_teacher'],

            // Normal teacher
            ['roles' => ['teacher'], 'route' => 'dashboard.teacher'],
        ];

       
        foreach ($map as $item) {
            if ($user->hasAnyRole($item['roles'])) {
                return redirect()->route($item['route']);
            }
        }

        // Fallback view (no role assigned)
        return view('home', compact('user'));
    }


    // =======================
    // DASHBOARD VIEWS
    // =======================

    public function admin()         { return $this->view('dashboards.admin'); }
    public function committee()     { return $this->view('dashboards.committee'); }
    public function classSupervisor(){ return $this->view('dashboards.class_supervisor'); }
    public function classTeacher()  { return $this->view('dashboards.class_teacher'); }
    public function teacher()       { return $this->view('dashboards.teacher'); }

    private function view($path)
    {
        return view($path, ['user' => Auth::user()]);
    }
}
