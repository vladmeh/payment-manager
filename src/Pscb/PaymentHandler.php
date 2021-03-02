<?php

namespace Vladmeh\PaymentManager\Pscb;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class PaymentHandler
{
    /**
     * @param string $url
     * @param string $messageText
     * @param string $signature
     *
     * @return Response
     */
    public function send(string $url, string $messageText, string $signature): Response
    {
        return Http::baseUrl(config('payment.pscb.merchantApiUrl'))
            ->withHeaders([
                'signature' => $signature
            ])
            ->withBody($messageText, 'application/json')
            ->post($url);
    }
}
