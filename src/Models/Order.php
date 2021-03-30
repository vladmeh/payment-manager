<?php

namespace Vladmeh\PaymentManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ramsey\Uuid\Nonstandard\Uuid;
use Vladmeh\PaymentManager\Casts\PaymentJson;
use Vladmeh\PaymentManager\Contracts\PaymentOrder;
use Vladmeh\PaymentManager\Order\PaymentOrderTrait;

class Order extends Model implements PaymentOrder
{
    use PaymentOrderTrait;

    public $incrementing = false;

    protected $guarded = [];
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    protected $attributes = [
        'details' => ''
    ];

    protected $casts = [
        'payment' => PaymentJson::class,
    ];

    /**
     * @param $value
     */
    public function setCreatedAtAttribute($value)
    {
        $this->attributes['created_at'] = $value;
        $this->attributes['uuid'] = Uuid::uuid6();
    }

    /**
     * @return HasMany
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'uuid');
    }

    /**
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * @param string $state
     * @return void
     */
    public function setStatus(string $state)
    {
        self::update(['state' => $state]);
    }
}
