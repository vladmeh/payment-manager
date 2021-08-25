<?php

namespace Fh\PaymentManager\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static Customer firstOrCreate(string[] $keys, string[] $attributes = [])
 * @property int id
 * @property string account
 * @property string email
 * @property string phone
 */
class Customer extends Model
{
    public $timestamps = false;
    protected $table = 'purchase_customers';
    protected $guarded = [];
    protected $attributes = [
        'email' => '',
        'phone' => '',
    ];

    /**
     * @return HasMany
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'customer_id', 'id');
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}