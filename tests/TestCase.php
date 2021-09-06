<?php

namespace Fh\PaymentManager\Tests;

use Fh\PaymentManager\Facades\Payment;
use Fh\PaymentManager\PaymentServiceProvider;
use Fh\PaymentManager\Pscb\PscbServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public static function assertIsUrl($actual, string $message = '')
    {
        static::assertTrue(!!filter_var($actual, FILTER_VALIDATE_URL), $message);
    }

    protected function getPackageProviders($app): array
    {
        return [
            PaymentServiceProvider::class,
            PscbServiceProvider::class
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'Payment' => Payment::class,
        ];
    }
}
