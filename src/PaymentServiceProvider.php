<?php

namespace Vladmeh\PaymentManager;

use Illuminate\Support\ServiceProvider;
use Vladmeh\PaymentManager\Pscb\PaymentHandler;
use Vladmeh\PaymentManager\Pscb\PaymentService;

class PaymentServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/payment.php',
            'payment'
        );

        $this->app->bind(PaymentService::class, function ($app) {
            return new PaymentService($app->make(PaymentHandler::class));
        });
    }

    public function boot()
    {
        $this->registerMigrations();
        $this->registerPublishing();
    }

    private function registerMigrations()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }
    }

    private function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/payment.php' => config_path('payment.php'),
            ], 'payment-config');

            $this->publishes([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ], 'payment-migrations');

            $this->publishes([
                __DIR__ . '/../database/factories' => database_path('factories'),
            ], 'payment-factories');
        }
    }
}
