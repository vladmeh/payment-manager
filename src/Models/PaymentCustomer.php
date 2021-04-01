<?php

namespace Fh\PaymentManager\Models;

use Illuminate\Database\Eloquent\Model;
use Fh\PaymentManager\Contracts\PayableCustomer;
use Fh\PaymentManager\Order\PaymentCustomerTrait;

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
