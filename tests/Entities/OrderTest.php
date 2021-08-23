<?php

namespace Fh\PaymentManager\Tests\Entities;

use Fh\PaymentManager\Entities\Order;
use Fh\PaymentManager\Entities\OrderItem;
use Fh\PaymentManager\Pscb\PaymentStatus;
use Fh\PaymentManager\Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Ramsey\Uuid\Uuid;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function testCreate()
    {
        $order = Order::create();

        $this->assertInstanceOf(Order::class, $order);
        $this->assertDatabaseCount('purchase_orders', 1);
        $this->assertDatabaseHas('purchase_orders', [
            'total' => 0,
            'amount' => 0.00,
            'status' => PaymentStatus::NEW
        ]);
    }

    public function testSetCreatedAtAttribute()
    {
        $order = Order::create();
        $this->assertIsString($order->uuid);
        $this->assertTrue(Uuid::isValid($order->uuid));
    }

    public function testAddOrderItem()
    {
        $order = Order::create();
        $order->addOrderItem(OrderItem::create([
            'name' => 'Тестовая услуга',
            'price' => 100.00,
            'details' => ["type" => "test", "name" => "Тестовая услуга", "price" => "100"]
        ]));

        $this->assertDatabaseCount('purchase_order_items', 1);
        $this->assertInstanceOf(Collection::class, $order->items);
        $this->assertInstanceOf(OrderItem::class, $order->items()->first());

        $this->assertEquals(1, $order->items->count());
        $this->assertEquals($order->items()->first()->quantity, $order->total);
        $this->assertEquals($order->items()->first()->quantity * $order->items()->first()->price, $order->amount);
        $this->assertEquals(PaymentStatus::NEW, $order->status);
    }
}
