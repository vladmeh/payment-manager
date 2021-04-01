<?php

namespace Fh\PaymentManager\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Fh\PaymentManager\PaymentServiceProvider;

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
}
