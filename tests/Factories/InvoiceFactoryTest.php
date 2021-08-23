<?php

namespace Fh\PaymentManager\Tests\Factories;

use Fh\PaymentManager\Entities\Customer;
use Fh\PaymentManager\Entities\Invoice;
use Fh\PaymentManager\Entities\Order;
use Fh\PaymentManager\Entities\OrderItem;
use Fh\PaymentManager\Facades\InvoiceFactoryFacade as InvoiceFactory;
use Fh\PaymentManager\Tests\Fixtures\People;
use Fh\PaymentManager\Tests\Fixtures\Product;
use Fh\PaymentManager\Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvoiceFactoryTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateInvoice()
    {
        $customer = new People;
        $product = new Product;

        $invoice = InvoiceFactory::createInvoice($customer, $product);

        $this->assertDatabaseCount('purchase_invoices', 1);
        $this->assertInstanceOf(Invoice::class, $invoice);

        $this->assertDatabaseCount('purchase_orders', 1);
        $this->assertEquals(1, Invoice::has('order')->count());
        $this->assertInstanceOf(Order::class, $invoice->order);

        $this->assertDatabaseCount('purchase_order_items', 1);
        $this->assertInstanceOf(Collection::class, $invoice->order->items);
        $this->assertInstanceOf(OrderItem::class, $invoice->order->items()->first());

        $this->assertDatabaseCount('purchase_customers', 1);
        $this->assertEquals(1, Invoice::has('customer')->count());
        $this->assertInstanceOf(Customer::class, $invoice->customer);
    }
}
