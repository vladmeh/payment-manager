<?php

namespace Fh\PaymentManager\Tests;

use Fh\PaymentManager\Facades\OrderFactoryFacade;
use Fh\PaymentManager\PaymentServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withFactories(__DIR__ . '/../database/factories');
    }

    protected function getPackageProviders($app): array
    {
        return [
            PaymentServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'OrderFactoryFacade' => OrderFactoryFacade::class,
        ];
    }
}
