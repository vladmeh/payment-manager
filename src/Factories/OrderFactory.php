<?php

namespace Fh\PaymentManager\Factories;

use Fh\PaymentManager\Contracts\PayableProduct;
use Fh\PaymentManager\Entities\Order;
use Fh\PaymentManager\Entities\OrderItem;

class OrderFactory
{
    /**
     * @param mixed $product
     * @return Order
     */
    public function createOrder($product): Order
    {
        $orderItem = $this->createOrderItem($product);

        $order = Order::create();
        $order->addOrderItem($orderItem);

        return $order;
    }

    /**
     * @param array|PayableProduct $product
     * @return array
     */
    private function orderItemAttributes($product): array
    {
        $attributes = [];

        if ($product instanceof PayableProduct) {
            $attributes = [
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'details' => $product->toArray()
            ];
        }

        if (is_array($product)) {
            if (key_exists('name', $product)) {
                $attributes['name'] = $product['name'];
            }

            if (!empty($attributes) && key_exists('price', $product)) {
                $attributes['price'] = $product['price'];
            }

            if (!empty($attributes)) {
                $attributes['details'] = $product;
            }
        }

        if (empty($attributes)) {
            throw new \InvalidArgumentException("Невозможно создать OrderItem. Некорректный Product");
        }

        return $attributes;
    }

    /**
     * @param $product
     * @return OrderItem
     */
    private function createOrderItem($product): OrderItem
    {
        return OrderItem::create($this->orderItemAttributes($product));
    }
}