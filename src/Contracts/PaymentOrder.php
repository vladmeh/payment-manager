<?php

namespace Vladmeh\PaymentManager\Contracts;

interface PaymentOrder
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
     * @param PaymentCustomer $customer
     * @return mixed
     */
    public function setCustomer(PaymentCustomer $customer);
}
