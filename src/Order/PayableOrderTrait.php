<?php

namespace Fh\PaymentManager\Order;

use Fh\PaymentManager\Contracts\PayableCustomer;

/**
 * @property int amount
 * @property string uuid
 */
trait PayableOrderTrait
{
    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getOrderId(): string
    {
        return $this->uuid;
    }

    /**
     * @param PayableCustomer $customer
     * @return void
     */
    public function setCustomer(PayableCustomer $customer)
    {
        $this->customer()
            ->associate($customer)
            ->save();
    }
}
