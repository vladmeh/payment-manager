<?php

namespace Vladmeh\PaymentManager\Tests\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Vladmeh\PaymentManager\Models\Order;
use Vladmeh\PaymentManager\Order\OrderStatus;
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
            ->create(['amount' => 100, 'details' => 'Тестовая услуга']);

        $this->assertDatabaseHas('orders', ['amount' => 100, 'details' => 'Тестовая услуга']);
        $this->assertEquals(100, $order->amount);
        $this->assertEquals('Тестовая услуга', $order->details);
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
    public function testOrderStatus(): void
    {
        $status = OrderStatus::status('create');

        $this->assertEquals(OrderStatus::CREATE, $status);
    }
}
