<?php

namespace Vladmeh\PaymentManager\Contracts;

interface PayableOrder
{
    /**
     * @return int
     */
    public function getAmount(): int;

    /**
     * @return string
     */
    public function getOrderId(): string;

    /**
     * @param PayableCustomer $customer
     * @return void
     */
    public function setCustomer(PayableCustomer $customer);
}
