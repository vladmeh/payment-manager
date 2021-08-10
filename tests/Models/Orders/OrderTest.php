<?php

namespace Fh\PaymentManager\Tests\Models\Orders;

use Fh\PaymentManager\Models\Orders\Order;
use Fh\PaymentManager\Tests\Fixtures\BuyableProduct;
use Fh\PaymentManager\Tests\TestCase;

class OrderTest extends TestCase
{
    const WISHLIST_INSTANCE = 'wishlist';

    /**
     * @var Order
     */
    private $order;

    /**
     * @test
     */
    public function it_has_a_default_instance(): void
    {
        $this->assertEquals(Order::DEFAULT_INSTANCE, $this->order->currentInstance());
    }

    /**
     * @test
     */
    public function it_can_have_multiple_instance(): void
    {
        $this->order->addItem(new BuyableProduct(1, 'First item'));
        $this->order->instance(self::WISHLIST_INSTANCE)->addItem(new BuyableProduct(2, 'Second item'));

        $this->assertItemsInOrder(1, $this->order->instance(Order::DEFAULT_INSTANCE));
        $this->assertItemsInOrder(1, $this->order->instance(self::WISHLIST_INSTANCE));
    }

    private function assertItemsInOrder(int $items, Order $order)
    {
        $actual = $order->count();

        $this->assertEquals($items, $actual, "Expected the cart to contain {$items} items, but got {$actual}.");
    }

    /**
     * @test
     */
    public function testContent()
    {

    }

    /**
     * @test
     */
    public function testGetItem()
    {

    }

    /**
     * @test
     */
    public function testAddItem()
    {

    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->order = new Order();
    }
}
