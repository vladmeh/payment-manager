<?php

namespace Vladmeh\PaymentManager\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int price
 * @property int quantity
 * @property int amount
 */
class OrderItem extends Model
{
    protected $guarded = [];

    protected $appends = ['amount'];

    public function getAmountAttribute()
    {
        return $this->price * $this->quantity;
    }
}
