<?php

namespace Fh\PaymentManager\Entities;

use Fh\PaymentManager\Pscb\PaymentStatus;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ramsey\Uuid\Nonstandard\Uuid;

/**
 * @method static self create(array $attributes = [])
 * @property Collection items
 * @property int total
 * @property float amount
 * @property string status
 */
class Order extends Model
{
    public $incrementing = false;

    protected $table = 'purchase_orders';
    protected $guarded = [];
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';

    protected $attributes = [
        'total' => 0,
        'amount' => 0.00,
        'status' => PaymentStatus::NEW
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
     * @param OrderItem $orderItem
     * @return void
     */
    public function addOrderItem(OrderItem $orderItem): void
    {
        $this->items()->save($orderItem);
        $this->updateTotalAmount();
    }

    /**
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'uuid');
    }

    /**
     * @return void
     */
    private function updateTotalAmount(): void
    {
        foreach ($this->items as $item) {
            $this->total += $item->quantity;
            $this->amount += $item->price * $item->quantity;
        }

        $this->save();
    }
}