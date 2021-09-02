<?php

namespace Fh\PaymentManager\Facades;

use Fh\PaymentManager\Queries\PaymentQuery;
use Illuminate\Support\Facades\Facade;

/**
 * @method static PaymentQuery create(\Closure $callback = null)
 */
class Query extends Facade
{
    /**
     * @param string $name
     * @return PaymentQuery
     */
    public static function paymentSystem(string $name): PaymentQuery
    {
        return static::$app['payment']->paymentSystem($name)->createQuery();
    }

    /**
     * @return PaymentQuery
     */
    protected static function getFacadeAccessor(): PaymentQuery
    {
        return static::$app['payment']->paymentSystem()->createQuery();
    }
}
