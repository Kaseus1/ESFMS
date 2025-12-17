<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use App\View\Composers\AdminComposer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // FIX: Force HTTPS if APP_URL starts with https (fixes local mixed content issues)
        if (str_starts_with(config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        // Use Tailwind for pagination
        Paginator::useTailwind();

        // Apply AdminComposer to the layout so variables are available on ALL admin pages
        View::composer([
            'layouts.admin', 
        ], AdminComposer::class);
    }
}