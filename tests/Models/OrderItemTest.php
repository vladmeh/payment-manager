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
            'quantity' => 1,
            'name' => 'Тестовая услуга',
            'price' => 100
        ];

        $orderItem = factory(OrderItem::class)->create($data);

        $this->assertInstanceOf(OrderItem::class, $orderItem);
        $this->assertDatabaseHas('order_items', $data);
    }
}
