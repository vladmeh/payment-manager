<?php

namespace Fh\PaymentManager\Services;

use Fh\PaymentManager\Contracts\PayableCustomer;
use Fh\PaymentManager\Contracts\PayableProduct;
use Fh\PaymentManager\Entities\Invoice;
use Fh\PaymentManager\Events\InvoiceCreated;
use Fh\PaymentManager\Facades\InvoiceFactoryFacade as InvoiceFactory;
use Fh\PaymentManager\Facades\PaymentQueryFacade as PaymentQuery;
use Fh\PaymentManager\Payments\QueryBuilder;

class Purchase
{
    /**
     * Создает счет на оплату
     *
     * @param PayableCustomer $customer
     * @param PayableProduct $product
     * @return Invoice
     */
    public function createInvoice(PayableCustomer $customer, PayableProduct $product): Invoice
    {
        return tap(InvoiceFactory::createInvoice($customer, $product), function ($invoice) {
            event(new InvoiceCreated($invoice));
        });
    }

    /**
     * @param string $typeRequest
     * @param Invoice $invoice
     * @param string $paymentSystem
     * @return QueryBuilder
     */
    public function paymentQuery(string $typeRequest, Invoice $invoice, string $paymentSystem = ''): QueryBuilder
    {
        return $this->getPaymentQuery($paymentSystem, $invoice);
    }

    /**
     * @param string $paymentSystem
     * @param Invoice $invoice
     * @return QueryBuilder
     */
    private function getPaymentQuery(string $paymentSystem, Invoice $invoice): QueryBuilder
    {
        return PaymentQuery::paymentSystem($paymentSystem)->create(function (QueryBuilder $query) use ($invoice) {
            $query->amount($invoice->getAmount());
            $query->orderId($invoice->getOrderId());
            $query->customer($invoice->customer->toArray());
        });
    }
}
