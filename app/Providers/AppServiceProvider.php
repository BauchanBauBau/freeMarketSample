<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/*
参考）
https://normalblog.net/system/laravel-migrate-error/
*/
use Illuminate\Support\Facades\Schema;

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
        if (\App::environment('production')) {
            \URL::forceScheme('https');
        }
        Schema::defaultStringLength(191);
    }
}
