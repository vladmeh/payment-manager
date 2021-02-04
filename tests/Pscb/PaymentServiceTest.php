<?php

namespace Vladmeh\PaymentManager\Tests\Pscb;

use Vladmeh\PaymentManager\Pscb\PaymentCustomer;
use Vladmeh\PaymentManager\Pscb\PaymentOrder;
use Vladmeh\PaymentManager\Pscb\PaymentService;
use Vladmeh\PaymentManager\Tests\TestCase;

class PaymentServiceTest extends TestCase
{
    private $paymentService;

    private $order;

    private $customer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->paymentService = new PaymentService();

        $this->order = new class implements PaymentOrder {

            public function getAmount(): int
            {
                return 200;
            }

            public function getOrderId(): string
            {
                return '123';
            }
        };

        $this->customer = new class implements PaymentCustomer {

            public function getAccount(): string
            {
                return '1234567890';
            }

            public function getEmail(): string
            {
                return 'customer@mail.test';
            }

            public function getPhone(): string
            {
                return '+7(123)456-78-90';
            }

            public function getComment(): string
            {
                return 'Comment';
            }
        };
    }

    /**
     * @test
     */
    public function createPayment(): void
    {
        $paymentRequest = $this->paymentService->createDataPayment($this->order, $this->customer);
        $message = $paymentRequest->toArray();

        $this->assertIsArray($message);

        $this->assertArrayHasKey('amount', $message);
        $this->assertArrayHasKey('orderId', $message);
        $this->assertArrayHasKey('customerAccount', $message);
        $this->assertArrayHasKey('customerComment', $message);
        $this->assertArrayHasKey('customerEmail', $message);
        $this->assertArrayHasKey('customerPhone', $message);

        $this->assertEquals($this->order->getAmount(), $message['amount']);
        $this->assertEquals($this->order->getOrderId(), $message['orderId']);
        $this->assertEquals($this->customer->getAccount(), $message['customerAccount']);
        $this->assertEquals($this->customer->getComment(), $message['customerComment']);
        $this->assertEquals($this->customer->getEmail(), $message['customerEmail']);
        $this->assertEquals($this->customer->getPhone(), $message['customerPhone']);

        $this->assertArrayNotHasKey('showOrderId', $message);
    }

    /**
     * @test
     */
    public function it_can_be_create_payment_with_params(): void
    {
        $successUrl = 'http://test.example.com/success';
        $failUrl = 'http://test.example.com/fail';
        $data = ['debug' => true, 'hold' => false];
        $params = compact('successUrl', 'failUrl', 'data');

        $paymentRequest = $this->paymentService->createDataPayment($this->order, $this->customer, $params);
        $message = $paymentRequest->toArray();

        $this->assertIsArray($message);

        $this->assertArrayHasKey('successUrl', $message);
        $this->assertArrayHasKey('failUrl', $message);
        $this->assertArrayHasKey('data', $message);

        $this->assertIsArray($message['data']);
        $this->assertArrayHasKey('debug', $message['data']);
        $this->assertArrayHasKey('hold', $message['data']);

        $this->assertEquals($successUrl, $message['successUrl']);
        $this->assertEquals($failUrl, $message['failUrl']);
    }
}
