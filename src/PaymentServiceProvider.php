<?php

namespace Vladmeh\PaymentManager;

use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/config.php',
            'package'
        );
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('package.php'),
            ], 'package-config');

//            $this->publishes([
//                __DIR__ . '/../database/migrations' => database_path('migrations'),
//            ], 'package-migrations');
        }
    }
}
