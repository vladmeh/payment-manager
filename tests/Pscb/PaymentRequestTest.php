<?php

namespace Vladmeh\PaymentManager\Tests\Pscb;

use Vladmeh\PaymentManager\Pscb\PaymentRequest;
use Vladmeh\PaymentManager\Tests\TestCase;

class PaymentRequestTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_init_config_params(): void
    {
        $paymentRequest = new PaymentRequest(200, '123');
        $successUrl = config('payment.pscb.successUrl');
        $message = $paymentRequest->toArray();

        $this->assertIsArray($message);
        $this->assertEquals($message['successUrl'], $successUrl);
    }

    /**
     * @test
     */
    public function it_can_be_init_with_params(): void
    {
        $successUrl = 'http://test.example.com/success';
        $failUrl = 'http://test.example.com/fail';
        $params = compact('successUrl', 'failUrl');
        $paymentRequest = new PaymentRequest(200, '123', $params);
        $message = $paymentRequest->toArray();

        $this->assertIsArray($message);
        $this->assertEquals($message['amount'], 200);
        $this->assertEquals($message['orderId'], '123');
        $this->assertEquals($message['successUrl'], $successUrl);
        $this->assertEquals($message['failUrl'], $failUrl);
    }
}
