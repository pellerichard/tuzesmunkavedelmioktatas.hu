<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class Helper extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public static function isTest() {
        // return (bool)config('app.debug');
        return true;
    }
}
