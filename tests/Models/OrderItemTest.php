<?php

namespace Vladmeh\PaymentManager\Tests\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Vladmeh\PaymentManager\Models\OrderItem;
use Vladmeh\PaymentManager\Tests\TestCase;

class OrderItemTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_be_create_order_item(): void
    {
        $data = [
            'text' => 'Тестовая услуга',
            'price' => 100,
        ];

        $orderItem = factory(OrderItem::class)->create($data);

        $this->assertDatabaseCount('order_items', 1);
        $this->assertDatabaseHas('order_items', $data);
        $this->assertEquals('Тестовая услуга', $orderItem->text);
        $this->assertEquals(100, $orderItem->price);
        $this->assertEquals(1, $orderItem->quantity);
        $this->assertEquals(100, $orderItem->amount);
    }

    /**
     * @test
     */
    public function it_can_be_get_amount_more_quantity(): void
    {
        $data = [
            'text' => 'Тестовая услуга',
            'price' => 100,
            'quantity' => 3
        ];

        $orderItem = factory(OrderItem::class)->create($data);
        $this->assertEquals(300, $orderItem->amount);
    }
}
