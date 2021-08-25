<?php

namespace Fh\PaymentManager\Entities;

use Fh\PaymentManager\Casts\PaymentResponse;
use Fh\PaymentManager\Support\HideTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static Invoice create(array $attributes = [])
 * @property PaymentResponse|null payment
 * @property Order order
 * @property Customer customer
 * @property string order_id
 * @property int id
 */
class Invoice extends Model
{
    use HideTimestamps;

    protected $table = 'purchase_invoices';
    protected $guarded = [];

    protected $casts = [
        'payment' => PaymentResponse::class,
    ];

    /**
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'uuid');
    }

    /**
     * @return string
     */
    public function getOrderId(): string
    {
        return $this->order_id;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->order->amount;
    }
}
