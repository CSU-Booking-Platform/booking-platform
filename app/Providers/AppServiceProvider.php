<?php

namespace App\Providers;

use App\Models\Settings;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\DuskServiceProvider;

/**
 * Vendor-published file, contains no business logic.
 *
 * @codeCoverageIgnore
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local', 'testing')) {
          $this->app->register(DuskServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (!$this->app->runningInConsole()){
            $logo = Settings::where('slug', 'app_logo')->first()->data['path'] ?? 'img/default_app_logo.png';
            Config::set('app.logo', $logo);
        }
    }
}
