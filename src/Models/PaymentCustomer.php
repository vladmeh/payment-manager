<?php

namespace Fh\PaymentManager\Models;

use Fh\PaymentManager\Contracts\PayableCustomer;
use Fh\PaymentManager\Order\PaymentCustomerTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static Model|self create(array $attributes = [])
 * @method static Model|self make(array $attributes = [])
 * @method static Model|self firstOrCreate(array $attributes, array $values = [])
 * @method static Model|self updateOrCreate(array $attributes, array $values = [])
 */
class PaymentCustomer extends Model implements PayableCustomer
{
    use PaymentCustomerTrait;

    public $timestamps = false;
    protected $table = 'payment_customers';
    protected $guarded = [];
    protected $attributes = [
        'email' => '',
        'phone' => '',
        'comment' => '',
    ];
}
