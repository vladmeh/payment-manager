<?php

namespace Fh\PaymentManager\Entities;

use Fh\PaymentManager\Casts\PaymentResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static Invoice create(array $attributes = [])
 * @property PaymentResponse|null payment
 */
class Invoice extends Model
{
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
}
