<?php

namespace Fh\PaymentManager\Tests\Entities;

use Fh\PaymentManager\Entities\OrderItem;
use Fh\PaymentManager\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderItemTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_be_create_order_item(): void
    {
        $data = [
            'name' => 'Тестовая услуга',
            'price' => 100.00,
        ];

        $orderItem = factory(OrderItem::class)->create($data);

        $this->assertDatabaseCount('purchase_order_items', 1);
        $this->assertDatabaseHas('purchase_order_items', $data);
        $this->assertEquals('Тестовая услуга', $orderItem->name);
        $this->assertEquals(100.00, $orderItem->price);
        $this->assertNull($orderItem->details);
        $this->assertEquals(1, $orderItem->quantity);
    }

    /**
     * @test
     */
    public function it_can_be_set_order_details(): void
    {
        $data = [
            'name' => 'Тестовая услуга',
            'price' => 100.00,
            'details' => ["type" => "test", "name" => "Тестовая услуга", "price" => "100"]
        ];

        $orderItem = factory(OrderItem::class)->create($data);

        $this->assertNotNull($orderItem->details);
        $this->assertIsArray($orderItem->details);
    }
}
