<?php

declare(strict_types=1);

namespace Vladmeh\PaymentManager\Pscb;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class OrderPaymentRequest
{
    /**
     * @param string $url
     * @param string $messageText
     * @return Response
     */
    public function sendMessage(string $url, string $messageText): Response
    {
        $signature = $this->signature($messageText);

        return Http::baseUrl(config('payment.pscb.merchantApiUrl'))
            ->withHeaders([
                'signature' => $signature
            ])
            ->withBody($messageText, 'application/json')
            ->post($url);
    }

    /**
     * Подпись сообщения с использованием ключа API secretKey.
     *
     * @param string $messageText JSON UTF8
     * @return string
     */
    final public function signature(string $messageText): string
    {
        return hash('sha256', $messageText . config('payment.pscb.secretKey'));
    }
}
