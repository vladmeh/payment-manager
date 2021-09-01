<?php

namespace Fh\PaymentManager;

use Fh\PaymentManager\Payments\PaymentFactory;
use Fh\PaymentManager\Payments\PaymentManager;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * @throws BindingResolutionException
     */
    public function register()
    {
        $this->registerLogging();
        $this->registerPublishing();

        $this->registerPaymentSystems();
    }

    /**
     * @throws BindingResolutionException
     */
    private function registerLogging()
    {
        $this->app->make('config')->set('logging.channels.payment', [
            'driver' => 'daily',
            'path' => storage_path('logs/payment/payment.log'),
            'level' => 'debug',
            'days' => 14,
        ]);
    }

    private function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/payment.php' => config_path('payment.php'),
            ], 'payment-config');
        }
    }

    private function registerPaymentSystems()
    {
        $this->app->singleton('payment.factory', function ($app) {
            return new PaymentFactory($app);
        });

        $this->app->singleton('payment', function ($app) {
            return new PaymentManager($app, $app['payment.factory']);
        });

        $this->app->bind('payment.system', function ($app) {
            return $app['payment']->paymentSystem();
        });
    }

    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/payment.php',
            'payment'
        );
    }
}
