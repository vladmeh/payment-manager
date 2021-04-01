<?php

namespace Fh\PaymentManager\Models;

use Fh\PaymentManager\Casts\PaymentJson;
use Fh\PaymentManager\Contracts\PayableOrder;
use Fh\PaymentManager\Order\PayableOrderTrait;
use Fh\PaymentManager\Pscb\PaymentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ramsey\Uuid\Nonstandard\Uuid;

/**
 * @method static self find(string $orderId)
 * @property PaymentCustomer customer
 */
class PaymentOrder extends Model implements PayableOrder
{
    use PayableOrderTrait;

    public $incrementing = false;

    protected $table = 'orders';
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
     * @param $orderId
     * @return PayableOrder|null
     */
    public static function findById($orderId): ?PayableOrder
    {
        return self::find($orderId);
    }

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
        return $this->hasMany(PaymentOrderItem::class, 'order_id', 'uuid');
    }

    /**
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(PaymentCustomer::class);
    }

    /**
     * @param string $state
     * @return void
     */
    public function setStatus(string $state)
    {
        self::update(['state' => $state]);
    }

    /**
     * @param mixed $payment
     * @return void
     */
    public function setPayment($payment)
    {
        if (is_array($payment) && array_key_exists('payment', $payment)) {
            $paymentStatus = $payment['payment']['state']
                ? PaymentStatus::status($payment['payment']['state'])
                : PaymentStatus::UNDEF;

            $this->update([
                'payment' => $payment['payment'],
                'state' => $paymentStatus
            ]);
        }
    }

    /**
     * @param string $customerAccount
     * @return bool
     */
    public function hasCustomerAccount(string $customerAccount): bool
    {
        if (!$this->customer || $this->customer->account != $customerAccount) {
            return false;
        }

        return true;
    }
}
