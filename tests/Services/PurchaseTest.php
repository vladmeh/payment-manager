<?php

namespace Fh\PaymentManager\Tests\Services;

use Fh\PaymentManager\Entities\Invoice;
use Fh\PaymentManager\Events\InvoiceCreated;
use Fh\PaymentManager\Facades\PurchaseFacade as Purchase;
use Fh\PaymentManager\Payments\QueryBuilder;
use Fh\PaymentManager\Tests\Fixtures\People;
use Fh\PaymentManager\Tests\Fixtures\Product;
use Fh\PaymentManager\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateInvoice()
    {
        Event::fake([InvoiceCreated::class]);

        $customer = new People;
        $product = new Product;

        $invoice = Purchase::createInvoice($customer, $product);

        $this->assertInstanceOf(Invoice::class, $invoice);
        Event::assertDispatched(InvoiceCreated::class, function (InvoiceCreated $event) use ($invoice) {
            return $event->invoice->order_id === $invoice->getOrderId();
        });
    }

    public function testPayInvoice()
    {
        $customer = new People;
        $product = new Product;

        $invoice = Purchase::createInvoice($customer, $product);
        $query = Purchase::payInvoice('testType', $invoice);

        $this->assertInstanceOf(QueryBuilder::class, $query);
    }
}
