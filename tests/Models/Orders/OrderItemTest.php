<?php

namespace Fh\PaymentManager\Tests\Models\Orders;

use Fh\PaymentManager\Models\Orders\OrderItem;
use Fh\PaymentManager\Tests\Fixtures\BuyableProduct;
use Fh\PaymentManager\Tests\TestCase;

class OrderItemTest extends TestCase
{

    public function testFromAttributes()
    {
        $orderItem = OrderItem::fromAttributes(1, 'Some Item', 100, ['size' => 'XL', 'color' => 'red']);
        $orderItem->setQuantity(2);

        $this->assertEquals([
            'id' => 1,
            'name' => 'Some Item',
            'price' => 100,
            'rowId' => $orderItem->rowId,
            'qty' => 2,
            'options' => [
                'size' => 'XL',
                'color' => 'red'
            ],
        ], $orderItem->toArray());
    }

    public function testFromArray()
    {
        $orderItem = OrderItem::fromArray([
            'id' => 1,
            'name' => 'Some Item',
            'price' => 100,
            'options' => ['size' => 'XL', 'color' => 'red']
        ]);
        $orderItem->setQuantity(2);

        $this->assertEquals([
            'id' => 1,
            'name' => 'Some Item',
            'price' => 100,
            'rowId' => $orderItem->rowId,
            'qty' => 2,
            'options' => [
                'size' => 'XL',
                'color' => 'red'
            ],
        ], $orderItem->toArray());
    }

    public function testFromBuyable()
    {
        $orderItem = OrderItem::fromBuyable(new BuyableProduct(2, 'Some Item', 200));

        $this->assertInstanceOf(OrderItem::class, $orderItem);
        $this->assertEquals(2, $orderItem->id);
        $this->assertEquals('Some Item', $orderItem->name);
        $this->assertEquals(200, $orderItem->price);
        $this->assertEquals(1, $orderItem->qty);
    }

    public function testAssociate()
    {
        $product = new BuyableProduct();
        $orderItem = OrderItem::fromBuyable($product);

        $orderItem->associate($product);

        $this->assertNotNull($orderItem->model());
        $this->assertEquals(get_class($product), $orderItem->model());
    }

    /**
     * @test
     */
    public function it_can_be_cast_to_an_array(): void
    {
        $orderItem = new OrderItem(1, 'Some Item', 100, ['size' => 'XL', 'color' => 'red']);
        $orderItem->setQuantity(2);

        $this->assertEquals([
            'id' => 1,
            'name' => 'Some Item',
            'price' => 100,
            'rowId' => $orderItem->rowId,
            'qty' => 2,
            'options' => [
                'size' => 'XL',
                'color' => 'red'
            ],
        ], $orderItem->toArray());
    }

    /**
     * @test
     */
    public function it_can_be_cast_to_json(): void
    {
        $orderItem = new OrderItem(1, 'Some Item', 100, ['size' => 'XL', 'color' => 'red']);
        $orderItem->setQuantity(2);

        $this->assertJson($orderItem->toJson());

        $json = json_encode([
            'id' => 1,
            'rowId' => $orderItem->rowId,
            'name' => 'Some Item',
            'qty' => 2,
            'price' => 100,
            'options' => [
                'size' => 'XL',
                'color' => 'red'
            ]]);

        $this->assertEquals($json, $orderItem->toJson());
    }
}
