<?php

namespace Vladmeh\PaymentManager\Tests\Pscb;

use Vladmeh\PaymentManager\Pscb\MessageRequestBuilder;
use Vladmeh\PaymentManager\Tests\TestCase;

class PaymentRequestDataTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_init_config_params(): void
    {
        $paymentRequest = new MessageRequestBuilder(200, '123');
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
        $paymentRequest = new MessageRequestBuilder(200, '123', $params);
        $message = $paymentRequest->toArray();

        $this->assertIsArray($message);
        $this->assertEquals(200, $message['amount']);
        $this->assertEquals('123', $message['orderId']);
        $this->assertEquals($successUrl, $message['successUrl']);
        $this->assertEquals($failUrl, $message['failUrl']);

        $this->assertArrayNotHasKey('showOrderId', $message);
    }

    /**
     * @test
     */
    public function it_can_be_make_is_static(): void
    {
        $successUrl = 'http://test.example.com/success';
        $failUrl = 'http://test.example.com/fail';
        $params = compact('successUrl', 'failUrl');
        $paymentRequest = MessageRequestBuilder::make(200, '123', $params);
        $message = $paymentRequest->toArray();

        $this->assertIsArray($message);
        $this->assertEquals(200, $message['amount']);
        $this->assertEquals('123', $message['orderId']);
        $this->assertEquals($successUrl, $message['successUrl']);
        $this->assertEquals($failUrl, $message['failUrl']);

        $this->assertArrayNotHasKey('showOrderId', $message);
    }

    /**
     * @test
     */
    public function it_can_be_set_property_by_name(): void
    {
        $paymentRequest = MessageRequestBuilder::make(200, '123')
            ->setShowOrderId('123')
            ->setDetails('details')
            ->setPaymentMethod('ac')
            ->setCustomerAccount('1234567890')
            ->setCustomerComment('Customer comment')
            ->setCustomerEmail('customer@email.test')
            ->setCustomerPhone('+7(123)456-78-90')
            ->setSuccessUrl('http://test.example.com/success')
            ->setFailUrl('http://test.example.com/fail')
            ->setDisplayLanguage('RU')
            ->setNonce()
            ->setData(['debug' => true]);

        $message = $paymentRequest->toArray();

        $this->assertIsArray($message);
        $this->assertArrayHasKey('amount', $message);
        $this->assertArrayHasKey('orderId', $message);
        $this->assertArrayHasKey('showOrderId', $message);
        $this->assertArrayHasKey('details', $message);
        $this->assertArrayHasKey('paymentMethod', $message);
        $this->assertArrayHasKey('customerAccount', $message);
        $this->assertArrayHasKey('customerComment', $message);
        $this->assertArrayHasKey('customerEmail', $message);
        $this->assertArrayHasKey('customerPhone', $message);
        $this->assertArrayHasKey('successUrl', $message);
        $this->assertArrayHasKey('failUrl', $message);
        $this->assertArrayHasKey('nonce', $message);
        $this->assertArrayHasKey('data', $message);
        $this->assertArrayHasKey('debug', $message['data']);
    }

    /**
     * @test
     */
    public function it_can_be_get_data_to_json(): void
    {
        $paymentRequest = MessageRequestBuilder::make(200, '123')
            ->setShowOrderId('123')
            ->setDetails('details')
            ->setPaymentMethod('ac')
            ->setCustomerAccount('1234567890')
            ->setCustomerComment('Customer comment')
            ->setCustomerEmail('customer@email.test')
            ->setCustomerPhone('+7(123)456-78-90')
            ->setSuccessUrl('http://test.example.com/success')
            ->setFailUrl('http://test.example.com/fail')
            ->setDisplayLanguage('RU')
            ->setNonce()
            ->setData(['debug' => true, 'hold' => false]);

        $json = $paymentRequest->toJson();

        $this->assertJson($json);
    }
}
