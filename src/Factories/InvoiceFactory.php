<?php

namespace Fh\PaymentManager\Factories;

use Fh\PaymentManager\Contracts\PayableCustomer;
use Fh\PaymentManager\Contracts\PayableProduct;
use Fh\PaymentManager\Entities\Invoice;
use Fh\PaymentManager\Facades\CustomerFactoryFacade as CustomerFactory;
use Fh\PaymentManager\Facades\OrderFactoryFacade as OrderFactory;

class InvoiceFactory
{
    /**
     * @param PayableCustomer $customer
     * @param PayableProduct $product
     * @return Invoice
     */
    public function createInvoice(PayableCustomer $customer, PayableProduct $product): Invoice
    {
        $order = OrderFactory::createOrder($product);
        $customer = CustomerFactory::defineCustomer($customer);

        return Invoice::create([
            'customer_id' => $customer->getId(),
            'order_id' => $order->getId(),
        ]);
    }
}