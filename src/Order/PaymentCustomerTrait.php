<?php

namespace Vladmeh\PaymentManager\Order;

/**
 * @property string account
 * @property string email
 * @property string phone
 * @property string comment
 */
trait PaymentCustomerTrait
{
    public function getAccount(): string
    {
        return $this->account;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getComment(): string
    {
        return $this->comment;
    }
}
