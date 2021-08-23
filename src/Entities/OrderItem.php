<?php

namespace Fh\PaymentManager\Entities;

use Fh\PaymentManager\Casts\Json;
use Fh\PaymentManager\Support\HideTimestamps;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string name
 * @property float price
 * @property int quantity
 * @method static self create(array $array)
 */
class OrderItem extends Model
{
    use HideTimestamps;

    protected $table = 'purchase_order_items';
    protected $guarded = [];
    protected $attributes = [
        'quantity' => 1
    ];

    protected $casts = [
        'details' => Json::class,
    ];
}