<?php

namespace Fh\PaymentManager;

use Fh\PaymentManager\Contracts\PayableCustomer;
use Fh\PaymentManager\Contracts\PayableProduct;
use Fh\PaymentManager\Entities\Invoice;
use Fh\PaymentManager\Events\InvoiceCreated;
use Fh\PaymentManager\Facades\InvoiceFactoryFacade as InvoiceFactory;

class Purchase
{
    public function createInvoice(PayableCustomer $customer, PayableProduct $product): Invoice
    {
        return tap(InvoiceFactory::createInvoice($customer, $product), function ($invoice) {
            event(new InvoiceCreated($invoice));
        });
    }
}