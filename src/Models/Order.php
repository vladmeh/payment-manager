<?php

namespace Vladmeh\PaymentManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ramsey\Uuid\Nonstandard\Uuid;
use Vladmeh\PaymentManager\Contracts\PaymentOrder;
use Vladmeh\PaymentManager\Order\PaymentOrderTrait;

/**
 * @method static self find(string $orderId)
 */
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
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * @param Payment $payment
     * @return $this
     */
    public function setPayment(Payment $payment): self
    {
        $this->payment()
            ->associate($payment)
            ->save();

        return $this;
    }

    /**
     * @param string $state
     * @return $this
     */
    public function setStatus(string $state): self
    {
        self::update(['state' => $state]);

        return $this;
    }
}
