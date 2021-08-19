<?php

namespace Fh\PaymentManager\Tests\Fixtures;

use Fh\PaymentManager\Contracts\PayableProduct;

class Product implements PayableProduct
{

    private $type = 'test_product';
    private $name = 'Test product';
    private $price = 100.00;
    private $description = 'Testing product from PayableProduct';

    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'price' => $this->getPrice(),
            'description' => $this->description,
            'type' => $this->type,
        ];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }
}