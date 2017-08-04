<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HashServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton('hash', function () {
            return new SecureHasher;
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        return ['hash'];
    }
}
