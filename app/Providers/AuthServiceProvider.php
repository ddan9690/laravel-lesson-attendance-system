<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('admin', function($user) {
            return $user->role == 'admin' || $user->role == 'super';
        });

        Gate::define('super', function($user) {
            return $user->role == 'super';
        });

        Gate::define('jamadata', function ($user) {
            return $user->email == 'dancanotieno@nyabogirls';
        });
    }
}
