<?php

namespace Fh\PaymentManager\Facades;

use Fh\PaymentManager\Payments\QueryBuilder;
use Fh\PaymentManager\Services\PaymentQuery;
use Illuminate\Support\Facades\Facade;

/**
 * @method static QueryBuilder create(string $paymentSystem, \Closure $callback)
 */
class PaymentQueryFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return PaymentQuery::class;
    }
}
