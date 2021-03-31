<?php

namespace Vladmeh\PaymentManager\Models;

use Illuminate\Database\Eloquent\Model;
use Vladmeh\PaymentManager\Contracts\PayableCustomer;
use Vladmeh\PaymentManager\Order\PaymentCustomerTrait;

class PaymentCustomer extends Model implements PayableCustomer
{
    use PaymentCustomerTrait;

    protected $table = 'customers';

    public $timestamps = false;
    protected $guarded = [];
    protected $attributes = [
        'email' => '',
        'phone' => '',
        'comment' => '',
    ];
}
