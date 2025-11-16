<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('dashboard_route')) {
    function dashboard_route() {
        $user = Auth::user();
        if (!$user) return route('login'); // fallback

        $map = [
            ['roles' => ['super_admin', 'principal', 'deputy', 'dos', 'senior_teacher'], 'route' => 'dashboard.admin'],
            ['roles' => ['committee_member'], 'route' => 'dashboard.committee'],
            ['roles' => ['class_supervisor'], 'route' => 'dashboard.class_supervisor'],
            ['roles' => ['class_teacher'], 'route' => 'dashboard.class_teacher'],
            ['roles' => ['teacher'], 'route' => 'dashboard.teacher'],
        ];

        foreach ($map as $item) {
            if ($user->hasAnyRole($item['roles'])) {
                return route($item['route']);
            }
        }

        return route('home'); // fallback
    }
}
