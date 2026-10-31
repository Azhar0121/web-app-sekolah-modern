<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        Paginator::useBootstrapFive();

        View::composer(['partials.footer', 'partials.navbar', 'layouts.public', 'welcome', 'ppdb.*'], function ($view) {
            $settings = Setting::allKeyed();
            $view->with('schoolSettings', $settings);
            if (!array_key_exists('settings', $view->getData())) {
                $view->with('settings', $settings);
            }
        });
    }
}
