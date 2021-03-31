<?php

namespace Vladmeh\PaymentManager\Contracts;

interface PayableOrder
{
    /**
     * @param $orderId
     * @return PayableOrder|null
     */
    public static function findById($orderId): ?PayableOrder;

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

    /**
     * @param string $customerAccount
     * @return bool
     */
    public function hasCustomerAccount(string $customerAccount): bool;
}
