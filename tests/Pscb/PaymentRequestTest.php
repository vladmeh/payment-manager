<?php

namespace Fh\PaymentManager\Tests\Pscb;

use Fh\PaymentManager\Pscb\PaymentRequest;
use Fh\PaymentManager\Pscb\PscbQueryBuilder;
use Fh\PaymentManager\Tests\TestCase;
use Illuminate\Http\Client\Response;

class PaymentRequestTest extends TestCase
{
    const ORDER_ID = 'INVOICE-229396278';
    const MARKET_PLACE = '47607';
    const AMOUNT = 100.00;

    public function testSignature()
    {
        $query = (new PscbQueryBuilder)
            ->amount(self::AMOUNT)
            ->orderId(self::ORDER_ID);
        $message = $query->toJson();
        $signature = PaymentRequest::signature($message);

        $this->assertIsString($signature);
    }

    public function testSend()
    {
        $orderId = self::ORDER_ID;
        $marketPlace = self::MARKET_PLACE;

        $messageData = compact('orderId', 'marketPlace');
        $messageText = json_encode($messageData);

        $response = PaymentRequest::send('checkPayment', $messageText);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertJson($response->body());
    }
}
