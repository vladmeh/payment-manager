<?php

namespace Fh\PaymentManager\Facades;

use Fh\PaymentManager\Contracts\PayableCustomer;
use Fh\PaymentManager\Contracts\PayableProduct;
use Fh\PaymentManager\Entities\Invoice;
use Fh\PaymentManager\Services\Purchase;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Invoice createInvoice(PayableCustomer $customer, PayableProduct $product)
 * @method static payInvoice(string $typeRequest, Invoice $invoice, string $paymentSystem = '')
 */
class PurchaseFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return Purchase::class;
    }

}