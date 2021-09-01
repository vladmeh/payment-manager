<?php

namespace Fh\PaymentManager\Facades;

use Fh\PaymentManager\Contracts\QueryBuilder;
use Fh\PaymentManager\Queries\PaymentQuery;
use Illuminate\Support\Facades\Facade;

/**
 * @method static QueryBuilder create(\Closure $callback = null)
 */
class PaymentQueryFacade extends Facade
{
    /**
     * @param string $name
     * @return PaymentQuery
     */
    public static function paymentSystem(string $name): PaymentQuery
    {
        return static::$app['payment']->paymentSystem($name)->getQuery();
    }

    /**
     * @return PaymentQuery
     */
    protected static function getFacadeAccessor(): PaymentQuery
    {
        return static::$app['payment']->paymentSystem()->getQuery();
    }
}
