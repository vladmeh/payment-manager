<?php

namespace Fh\PaymentManager\Facades;

use Fh\PaymentManager\Payments\QueryBuilder;
use Fh\PaymentManager\Services\Payment;
use Illuminate\Support\Facades\Facade;

/**
 * @method static QueryBuilder createQuery(string $paymentSystem, \Closure $callback)
 */
class PaymentFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return Payment::class;
    }
}