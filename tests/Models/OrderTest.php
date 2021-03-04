<?php

namespace Vladmeh\PaymentManager\Tests\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Vladmeh\PaymentManager\Models\Order;
use Vladmeh\PaymentManager\Models\OrderItem;
use Vladmeh\PaymentManager\Models\Payment;
use Vladmeh\PaymentManager\Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function testCreateOrder(): void
    {
        $order = factory(Order::class)
            ->create(['amount' => 100, 'details' => 'Тестовая услуга', 'state' => 'create']);

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseHas('orders', ['amount' => 100, 'details' => 'Тестовая услуга']);
        $this->assertEquals(100, $order->amount);
        $this->assertEquals('Тестовая услуга', $order->details);
        $this->assertEquals('create', $order->state);
    }

    /**
     * @test
     */
    public function testGetAmount(): void
    {
        $order = factory(Order::class)
            ->create(['amount' => 100]);

        $this->assertEquals($order->amount, $order->getAmount());
    }

    /**
     * @test
     */
    public function testGetOrderId(): void
    {
        $order = factory(Order::class)
            ->create();

        $this->assertEquals($order->uuid, $order->getOrderId());
    }

    /**
     * @test
     */
    public function it_can_be_added_order_item_model(): void
    {
        $order = factory(Order::class)->create();
        $orderItem = factory(OrderItem::class)->create();
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
        $order = factory(Order::class)->create();
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
    public function it_can_be_set_payment(): void
    {
        $payment = factory(Payment::class)->create();
        $order = factory(Order::class)->create();

        $order->setPayment($payment);

        $this->assertInstanceOf(Payment::class, $order->payment);

        print_r($order->toArray());
    }

    /**
     * @test
     */
    public function it_can_be_set_status(): void
    {
        $status = 'created';

        $order = factory(Order::class)->create();
        $order->setStatus($status);

        $this->assertEquals($status, $order->state);
    }
}
