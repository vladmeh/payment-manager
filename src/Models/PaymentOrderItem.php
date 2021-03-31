<?php

namespace Vladmeh\PaymentManager\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int price
 * @property int quantity
 * @property int amount
 */
class PaymentOrderItem extends Model
{
    protected $table ='order_items';
    protected $guarded = [];
    protected $appends = ['amount'];
    protected $attributes = [
        'quantity' => 1
    ];

    public function getAmountAttribute()
    {
        return $this->price * $this->quantity;
    }
}
