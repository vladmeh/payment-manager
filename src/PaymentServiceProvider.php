<?php

namespace Fh\PaymentManager;

use Fh\PaymentManager\Pscb\OrderPaymentRequest;
use Fh\PaymentManager\Pscb\PaymentService;
use Fh\PaymentManager\Requests\NotificationRequest;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/payment.php',
            'payment'
        );

        $this->app->bind(PaymentService::class, function ($app) {
            return new PaymentService($app->make(OrderPaymentRequest::class));
        });
    }

    public function boot()
    {
        $this->registerLogging();
        $this->registerMigrations();
        $this->registerPublishing();

        $this->resolvingRequests();
    }

    private function registerLogging()
    {
        try {
            $this->app->make('config')->set('logging.channels.payment', [
                'driver' => 'daily',
                'path' => storage_path('logs/payment/payment.log'),
                'level' => 'debug',
                'days' => 14,
            ]);
        } catch (BindingResolutionException $e) {
            Log::error($e->getMessage());
        }
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

    private function resolvingRequests(): void
    {
        $this->app->resolving(NotificationRequest::class, function ($request, $app) {
            NotificationRequest::createFrom($app['request'], $request);
        });
    }
}
