<?php

namespace Vladmeh\PaymentManager\Support;

trait StatusTrait
{
    /**
     * @param string $state
     * @return mixed|string
     */
    public static function status(string $state): string
    {
        if (defined('self::STATUS')
            && is_array(self::STATUS)
            && array_key_exists($state, self::STATUS)) {

            return self::STATUS[$state];
        }

        return $state;
    }
}
