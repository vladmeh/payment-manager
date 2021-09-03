<?php

namespace Fh\PaymentManager\Pscb;

trait Signature
{
    /**
     * Подпись сообщения с использованием ключа API secretKey.
     *
     * @param string $dataJson JSON UTF8
     * @return string
     */
    final private function signature(string $dataJson): string
    {
        return hash('sha256', $dataJson . config('payment.pscb.secretKey'));
    }
}
