<?php

namespace Fh\PaymentManager\Facades;

use Fh\PaymentManager\Contracts\PaymentSystem;
use Fh\PaymentManager\Queries\PaymentQuery;
use Fh\PaymentManager\Requests\PaymentRequestHandler;
use Illuminate\Support\Facades\Facade;

class Payment extends Facade
{
    /**
     * @return PaymentSystem
     */
    protected static function getFacadeAccessor(): PaymentSystem
    {
        return static::$app['payment.system'];
    }

    /**
     * @param string $name
     * @return PaymentSystem
     */
    public static function system(string $name = ''): PaymentSystem
    {
        if ($name) {
            return static::$app['payment']->paymentSystem($name);
        }

        return static::$app['payment.system'];
    }

    /**
     * @return PaymentQuery
     */
    public static function query(): PaymentQuery
    {
        return static::$app['payment.system']->createQuery();
    }

    /**
     * @return PaymentRequestHandler
     */
    public static function requestHandler(): PaymentRequestHandler
    {
        return static::$app['payment.system']->requestHandler();
    }
}