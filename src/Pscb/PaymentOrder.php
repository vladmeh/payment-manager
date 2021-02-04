<?php


namespace Vladmeh\PaymentManager\Pscb;


interface PaymentOrder
{

    public function getAmount(): int;

    public function getOrderId(): string;
}