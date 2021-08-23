<?php

namespace Fh\PaymentManager\Support;

trait HideTimestamps
{
    /**
     * @return string[]
     */
    public function getHidden(): array
    {
        return [
            'created_at',
            'updated_at'
        ];
    }
}