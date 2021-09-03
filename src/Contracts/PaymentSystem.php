<?php

namespace Fh\PaymentManager\Contracts;

use Fh\PaymentManager\Queries\PaymentQuery;
use Fh\PaymentManager\Requests\PaymentRequestHandler;

interface PaymentSystem
{
    /**
     * @return PaymentQuery
     */
    public function createQuery(): PaymentQuery;

    /**
     * @return PaymentRequestHandler
     */
    public function createRequestHandler(): PaymentRequestHandler;
}
