<?php

namespace Fh\PaymentManager\Tests\Models;

use Fh\PaymentManager\Models\PaymentOrderItem;
use Fh\PaymentManager\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentOrderItemTest extends TestCase
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

        $orderItem = factory(PaymentOrderItem::class)->create($data);

        $this->assertDatabaseCount('payment_order_items', 1);
        $this->assertDatabaseHas('payment_order_items', $data);
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

        $orderItem = factory(PaymentOrderItem::class)->create($data);
        $this->assertEquals(300, $orderItem->amount);
    }
}
