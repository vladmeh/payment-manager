<?php

namespace Fh\PaymentManager\Pscb;

use Illuminate\Support\ServiceProvider;

class PscbServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerPscbService();
    }

    private function registerPscbService(): void
    {
        $this->app->bind('payment.system.pscb', function () {
            return new PaymentService();
        });
    }
}
