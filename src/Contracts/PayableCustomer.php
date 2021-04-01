<?php

namespace Fh\PaymentManager\Contracts;

interface PayableCustomer
{
    /**
     * @return string
     */
    public function getAccount(): string;

    /**
     * @return string
     */
    public function getEmail(): string;

    /**
     * @return string
     */
    public function getPhone(): string;

    /**
     * @return string
     */
    public function getComment(): string;
}
