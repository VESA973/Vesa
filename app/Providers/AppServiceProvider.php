<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\ServiceProvider;
use Throwable;

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
        // Mail settings saved in the admin override .env. Skipped while the database is not ready (install, migrations).
        try {
            Setting::applyMailConfig();
        } catch (Throwable) {
            // No settings table yet: keep the .env values.
        }
    }
}
