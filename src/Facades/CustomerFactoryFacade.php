<?php

namespace Fh\PaymentManager\Facades;

use Fh\PaymentManager\Entities\Customer;
use Fh\PaymentManager\Factories\CustomerFactory;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Customer defineCustomer($customer)
 */
class CustomerFactoryFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return CustomerFactory::class;
    }
}