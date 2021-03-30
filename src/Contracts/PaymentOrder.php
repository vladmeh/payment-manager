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
     * @return void
     */
    public function setCustomer(PaymentCustomer $customer);

    /**
     * @param mixed $payment
     * @return mixed|void
     */
    public function setPayment($payment);
}
