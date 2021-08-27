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
        $this->app->bind('payment.system.pscb', function () {
            return new PaymentService();
        });
    }

    private function resolvingRequests(): void
    {
        $this->app->resolving(NotificationRequest::class, function ($request, $app) {
            NotificationRequest::createFrom($app['request'], $request);
        });
    }
}
