<?php

namespace Fh\PaymentManager\Payments;

interface PaymentSystem
{
    public function getQuery(): PaymentQuery;
}
