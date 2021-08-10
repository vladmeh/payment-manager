<?php


namespace Fh\PaymentManager\Models\Orders;


use Illuminate\Support\Collection;

class OrderItemOptions extends Collection
{

    /**
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }
}