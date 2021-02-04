<?php


namespace Vladmeh\PaymentManager\Pscb;


interface PaymentCustomer
{
    public function getAccount(): string;

    public function getEmail(): string;

    public function getPhone(): string;

    public function getComment(): string;
}