<?php

namespace Vladmeh\PaymentManager\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Nonstandard\Uuid;
use Vladmeh\PaymentManager\Contracts\PaymentOrder;
use Vladmeh\PaymentManager\Order\PaymentOrderTrait;

class Order extends Model implements PaymentOrder
{
    use PaymentOrderTrait;

    public $incrementing = false;
    protected $guarded = [];
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';

    public function setCreatedAtAttribute($value)
    {
        $this->attributes['created_at'] = $value;
        $this->attributes['uuid'] = Uuid::uuid6();
    }
}
