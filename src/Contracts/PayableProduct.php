<?php

namespace Fh\PaymentManager\Contracts;

use Illuminate\Contracts\Support\Arrayable;

interface PayableProduct extends Arrayable
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return float
     */
    public function getPrice(): float;
}