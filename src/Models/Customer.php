<?php

namespace Vladmeh\PaymentManager\Models;

use Illuminate\Database\Eloquent\Model;
use Vladmeh\PaymentManager\Contracts\PayableCustomer;
use Vladmeh\PaymentManager\Order\PaymentCustomerTrait;

class Customer extends Model implements PayableCustomer
{
    use PaymentCustomerTrait;

    public $timestamps = false;
    protected $guarded = [];
    protected $attributes = [
        'email' => '',
        'phone' => '',
        'comment' => '',
    ];
}
