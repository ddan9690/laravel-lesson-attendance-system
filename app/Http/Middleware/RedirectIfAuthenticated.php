<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                $role = $user->getRoleNames()->first();

                // Use the SAME logic as login redirect
                return match($role) {
                    'super_admin', 'principal', 'deputy', 'dos', 'senior_teacher' 
                        => redirect()->route('dashboard.admin'),
                    'committee_member' 
                        => redirect()->route('dashboard.committee'),
                    'class_supervisor' 
                        => redirect()->route('dashboard.class_supervisor'),
                    'class_teacher' 
                        => redirect()->route('dashboard.class_teacher'),
                    default 
                        => redirect()->route('dashboard.teacher'),
                };
            }
        }

        return $next($request);
    }
}