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

        $this->assertInstanceOf(OrderItem::class, $orderItem);
        $this->assertDatabaseHas('order_items', $data);
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
