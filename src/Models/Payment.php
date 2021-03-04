<?php

namespace Vladmeh\PaymentManager\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static self updateOrCreate(array $attributes, array $values = [])
 * @property string state
 */
class Payment extends Model
{
    protected $guarded = [];

    public $timestamps = false;
}
