<?php

namespace Fh\PaymentManager\Facades;

use Fh\PaymentManager\Entities\Order;
use Fh\PaymentManager\Factories\OrderFactory;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Order createOrder($product)
 */
class OrderFactoryFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return OrderFactory::class;
    }
}