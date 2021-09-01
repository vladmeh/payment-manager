<?php

namespace Fh\PaymentManager\Contracts;


use Fh\PaymentManager\Queries\PaymentQuery;

interface PaymentSystem
{
    public function getQuery(): PaymentQuery;
}
