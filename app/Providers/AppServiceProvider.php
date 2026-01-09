<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use App\Services\AcademicContextService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // You can bind services here if needed later
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(AcademicContextService $academicContextService): void
    {
        /**
         * Force HTTPS in production
         */
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        /**
         * Use Bootstrap pagination
         */
        Paginator::useBootstrap();

        /**
         * Share Academic Year, Term & Week across ALL dashboards
         */
        View::composer('*', function ($view) use ($academicContextService) {
            $view->with(
                'academicContext',
                $academicContextService->getCurrentContext()
            );
        });
    }
}
