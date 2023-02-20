<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (config('telescope.enabled')) {
            \Laravel\Telescope\Telescope::ignoreMigrations();
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (config('app.force_https_scheme')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        Relation::enforceMorphMap([
            \App\Models\Role::RETAILER => 'Tagd\Core\Models\Actor\Retailer',
            \App\Models\Role::RESELLER => 'Tagd\Core\Models\Actor\Reseller',
            \App\Models\Role::CONSUMER => 'Tagd\Core\Models\Actor\Consumer',
        ]);

        Log::withContext([
            'app_name' => config('app.name'),
        ]);
    }
}
