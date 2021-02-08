<?php

namespace Vladmeh\PaymentManager\Contracts;

interface PaymentOrder
{
    public function getAmount(): int;

    public function getOrderId(): string;
}
