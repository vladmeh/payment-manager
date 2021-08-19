<?php

namespace Fh\PaymentManager\Factories;

use Fh\PaymentManager\Contracts\PayableProduct;
use Fh\PaymentManager\Entities\Order;
use Fh\PaymentManager\Entities\OrderItem;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

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
     * @param $product
     * @return OrderItem
     */
    private function createOrderItem($product): OrderItem
    {
        return OrderItem::create($this->getAttributes($product));
    }

    /**
     * @param array|PayableProduct $product
     * @return array
     */
    private function getAttributes($product): array
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
            $attributes = [
                'name' => Arr::get($product, 'name', ''),
                'price' => Arr::get($product, 'price', ''),
                'details' => $product
            ];
        }

        $this->validateAttributes($attributes);

        return $attributes;
    }

    private function validateAttributes(array $attributes)
    {
        $validator = Validator::make($attributes, [
            'name' => ['required', 'string'],
            'price' => ['required', 'numeric'],
            'details' => ['required', 'array']
        ]);

        if ($validator->fails()) {
            throw new \InvalidArgumentException("Невозможно создать OrderItem. Некорректный Product");
        }
    }
}