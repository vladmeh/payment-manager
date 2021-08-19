<?php

namespace Fh\PaymentManager\Tests\Fixtures;

use Fh\PaymentManager\Contracts\PayableCustomer;

class People implements PayableCustomer
{

    private $phone;
    private $email;

    /**
     * @param string $phone
     * @param string $email
     */
    public function __construct(string $phone, string $email)
    {
        $this->phone = $phone;
        $this->email = $email;
    }


    public function getAccount(): string
    {
        return phone_digits($this->getPhone());
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}