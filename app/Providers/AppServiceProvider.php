<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

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
    public function boot()
    {
        $url = parse_url(url('/'));
        if($url['scheme'] == 'https'){
            URL::forceScheme('https');
        }
        Carbon::setLocale(LC_TIME, $this->app->getLocale());
    }
}
