<?php

namespace Fh\PaymentManager\Models;

use Fh\PaymentManager\Contracts\PayableCustomer;
use Fh\PaymentManager\Order\PaymentCustomerTrait;
use Illuminate\Database\Eloquent\Model;

class PaymentCustomer extends Model implements PayableCustomer
{
    use PaymentCustomerTrait;

    public $timestamps = false;
    protected $table = 'customers';
    protected $guarded = [];
    protected $attributes = [
        'email' => '',
        'phone' => '',
        'comment' => '',
    ];
}
