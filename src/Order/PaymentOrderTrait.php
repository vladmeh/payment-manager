<?php

namespace Vladmeh\PaymentManager\Order;

/**
 * @property int amount
 * @property string uuid
 */
trait PaymentOrderTrait
{
    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getOrderId(): string
    {
        return $this->uuid;
    }
}
