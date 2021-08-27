<?php

namespace Fh\PaymentManager\Pscb;

use Illuminate\Support\ServiceProvider;

class PscbServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerPscbService();
        $this->resolvingRequests();
    }

    private function registerPscbService(): void
    {
        $this->app->bind('payment.system.pscb.request', function () {
            return new PaymentRequest;
        });

        $this->app->bind('payment.system.pscb', function ($app) {
            return new PaymentService($app['payment.system.pscb.request']);
        });
    }

    private function resolvingRequests(): void
    {
        $this->app->resolving(NotificationRequest::class, function ($request, $app) {
            NotificationRequest::createFrom($app['request'], $request);
        });
    }
}