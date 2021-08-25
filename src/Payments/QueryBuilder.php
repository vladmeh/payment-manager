<?php

namespace Fh\PaymentManager\Payments;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

interface QueryBuilder extends Arrayable, Jsonable
{
    /**
     * @param mixed $amount
     * @return mixed
     */
    public function amount($amount);

    /**
     * @param array $attributes
     * @return mixed
     */
    public function customer(array $attributes);

    /**
     * @param string $orderId
     * @return mixed
     */
    public function orderId(string $orderId);

    /**
     * @param string $paymentMethod
     * @return mixed
     */
    public function paymentMethod(string $paymentMethod);

    /**
     * @param string $successUrl
     * @return mixed
     */
    public function successUrl(string $successUrl);

    /**
     * @return string
     */
    public function getPayUrl(): string;
}