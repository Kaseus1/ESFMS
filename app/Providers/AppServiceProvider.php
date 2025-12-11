<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\LoginResponse;
use App\Http\Responses\LoginResponse as CustomLoginResponse;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
use App\View\Composers\AdminComposer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind our custom LoginResponse for role-based redirects
        $this->app->singleton(LoginResponse::class, CustomLoginResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS only in production (not in local development)
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
        
        // Use Tailwind for pagination
        Paginator::useTailwind();

        // ONLY apply AdminComposer to the actual dashboard view
        // This prevents dashboard stats from appearing on other admin pages
        View::composer([
            'dashboards.admin',
        ], AdminComposer::class);
    }
}