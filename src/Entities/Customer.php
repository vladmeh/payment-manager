<?php

namespace Fh\PaymentManager\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static Customer firstOrCreate(string[] $keys, string[] $attributes = [])
 */
class Customer extends Model
{
    public $timestamps = false;
    protected $table = 'purchase_customers';
    protected $guarded = [];
    protected $attributes = [
        'email' => '',
        'phone' => '',
    ];
}