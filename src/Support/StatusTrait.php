<?php

namespace Vladmeh\PaymentManager\Support;

trait StatusTrait
{
    /**
     * @param string $state
     * @return string
     */
    public static function status(string $state): string
    {
        if (array_key_exists($state, self::STATUS)) {
            return self::STATUS[$state];
        }

        return $state;
    }
}
