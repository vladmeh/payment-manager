<?php

namespace Fh\PaymentManager\Tests\Models;

use Fh\PaymentManager\Models\PaymentCustomer;
use Fh\PaymentManager\Models\PaymentOrder;
use Fh\PaymentManager\Models\PaymentOrderItem;
use Fh\PaymentManager\Pscb\PaymentStatus;
use Fh\PaymentManager\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function testCreateOrder(): void
    {
        $order = factory(PaymentOrder::class)
            ->create(['amount' => 100, 'details' => 'Тестовая услуга']);

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseHas('orders', ['amount' => 100, 'details' => 'Тестовая услуга']);
        $this->assertEquals(100, $order->amount);
        $this->assertEquals('Тестовая услуга', $order->details);
        $this->assertEquals(PaymentStatus::UNDEF, $order->state);
    }

    /**
     * @test
     */
    public function testGetAmount(): void
    {
        $order = factory(PaymentOrder::class)
            ->create(['amount' => 100]);

        $this->assertEquals($order->amount, $order->getAmount());
    }

    /**
     * @test
     */
    public function testGetOrderId(): void
    {
        $order = factory(PaymentOrder::class)
            ->create();

        $this->assertEquals($order->uuid, $order->getOrderId());
    }

    /**
     * @test
     */
    public function it_can_be_added_order_item_model(): void
    {
        $order = factory(PaymentOrder::class)->create();
        $orderItem = factory(PaymentOrderItem::class)->create();
        $order->orderItems()->save($orderItem);

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_items', 1);
        $this->assertEquals($order->uuid, $order->orderItems->first()->order_id);
    }

    /**
     * @test
     */
    public function it_can_be_added_order_item_from_attributes(): void
    {
        $order = factory(PaymentOrder::class)->create();
        $dataOrderItem = [
            'text' => 'Тестовая услуга',
            'price' => 100,
            'quantity' => 2,
        ];
        $order->orderItems()->create($dataOrderItem);

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_items', 1);
        $this->assertEquals($order->uuid, $order->orderItems->first()->order_id);
    }

    /**
     * @test
     */
    public function it_can_be_set_customer(): void
    {
        $customer = factory(PaymentCustomer::class)->create();
        $order = factory(PaymentOrder::class)->create();

        $order->setCustomer($customer);

        $this->assertInstanceOf(PaymentCustomer::class, $order->customer);
    }

    /**
     * @test
     */
    public function it_can_be_set_status(): void
    {
        $status = PaymentStatus::SENT;

        $order = factory(PaymentOrder::class)->create();
        $order->setStatus($status);

        $this->assertEquals($status, $order->state);
    }

    /**
     * @test
     */
    public function it_can_be_set_payment(): void
    {
        $order = factory(PaymentOrder::class)->create();
        $payment = [
            'orderId' => $order->uuid,
            'showOrderId' => '1585687620',
            'paymentId' => '245215353',
            'account' => '1234567890',
            'amount' => $order->amount,
            'state' => 'exp',
            'marketPlace' => '328150779',
            'paymentMethod' => 'ac',
            'stateDate' => '2020-04-01T00:52:57.268+03:00'
        ];

        $order->setPayment(['payment' => $payment]);

        $this->assertDatabaseHas('orders', ['payment' => json_encode($payment)]);
        $this->assertEquals($payment, $order->payment);
        $this->assertTrue($order->hasCast('payment'));
    }
}
