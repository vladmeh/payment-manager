<?php

namespace Vladmeh\PaymentManager\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class PaymentJson implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        return json_decode($value, true);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return json_encode($value);
    }
}
