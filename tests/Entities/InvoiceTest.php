<?php

namespace Fh\PaymentManager\Tests\Entities;

use Fh\PaymentManager\Entities\Customer;
use Fh\PaymentManager\Entities\Invoice;
use Fh\PaymentManager\Entities\Order;
use Fh\PaymentManager\Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Invoice
     */
    private $invoice;
    /**
     * @var Customer
     */
    private $customer;

    /**
     * @var Order
     */
    private $order;

    /**
     * @test
     */
    public function testCreate(): void
    {
        $this->assertInstanceOf(Invoice::class, $this->invoice);
        $this->assertDatabaseCount('purchase_invoices', 1);
        $this->assertDatabaseHas('purchase_invoices', [
            'customer_id' => $this->customer->id,
            'order_id' => $this->order->uuid,
        ]);

        $this->assertNull($this->invoice->payment);

        $this->assertEquals(1, Invoice::has('order')->count());
        $this->assertEquals(1, Invoice::has('customer')->count());

        $this->assertArrayNotHasKey('created_at', $this->invoice->toArray());
        $this->assertArrayNotHasKey('updated_at', $this->invoice->toArray());
    }

    public function testCustomer()
    {
        $this->assertDatabaseHas('purchase_invoices', [
            'customer_id' => $this->customer->id,
        ]);

        $this->assertEquals(1, Invoice::has('customer')->count());

        $this->assertInstanceOf(Customer::class, $this->invoice->customer);
        $this->assertInstanceOf(Collection::class, $this->customer->invoices);
        $this->assertInstanceOf(Invoice::class, $this->customer->invoices()->first());
    }

    public function testOrder()
    {
        $this->assertDatabaseHas('purchase_invoices', [
            'order_id' => $this->order->uuid,
        ]);

        $this->assertEquals(1, Invoice::has('order')->count());

        $this->assertInstanceOf(Order::class, $this->invoice->order);
        $this->assertInstanceOf(Invoice::class, $this->order->invoice);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->customer = factory(Customer::class)->create();
        $this->order = factory(Order::class)->create();
        $this->invoice = Invoice::create([
            'customer_id' => $this->customer->id,
            'order_id' => $this->order->uuid,
        ]);
    }
}
