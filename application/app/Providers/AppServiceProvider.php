<?php

namespace App\Providers;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url)
    {

        //[growcrm] disable debug bar in production mode
        if (!env('APP_DEBUG_TOOLBAR')) {
            \Debugbar::disable();
        }

        //[growcrm] force SSL rul's
        if (env('ENFORCE_SSL', false)) {
            $url->forceScheme('https');
        }

        //[growcrm] - use bootstrap css for paginator
        Paginator::useBootstrap();

        //[growcrm]
        $this->app->bind(Carbon::class, function (Container $container) {
            return new Carbon('now', 'Europe/Brussels');
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
