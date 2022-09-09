<?php

namespace App\Providers;
use Illuminate\Routing\UrlGenerator;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(URLGenerator $url)
    {

        if(env('APP_ENV') !== 'local')
        {
            $url->forceSchema('https');
        }

        date_default_timezone_set('Africa/Addis_Ababa');
    }
}
