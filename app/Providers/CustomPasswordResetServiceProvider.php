<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Passwords\PasswordBrokerManager;
use Illuminate\Auth\Passwords\PasswordBroker;

class CustomPasswordResetServiceProvider extends ServiceProvider
{
    function register()
    {
        $this->app->singleton('auth.password.broker', function ($app) {
            return new PasswordBrokerManager($app);
        });

        $this->app->bind('auth.password.broker.retailer', function ($app) {
            return $app->make('auth.password')->broker('retailer');
        });
    }

    function boot(): void
    {
        //
    }
}
