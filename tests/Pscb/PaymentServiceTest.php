<?php

namespace Vladmeh\PaymentManager\Tests\Pscb;

use Illuminate\Http\Client\Response;
use Vladmeh\PaymentManager\Contracts\PaymentCustomer;
use Vladmeh\PaymentManager\Contracts\PaymentOrder;
use Vladmeh\PaymentManager\Pscb\PaymentService;
use Vladmeh\PaymentManager\Tests\TestCase;

class PaymentServiceTest extends TestCase
{
    private $paymentService;

    private $order;

    private $customer;

    private $response_message;

    /**
     * @test
     */
    public function testCreatePayment(): void
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

    /**
     * @test
     */
    public function testSignature(): void
    {
        $requestData = $this->paymentService->createDataPayment($this->order, $this->customer);
        $message = $requestData->toJson();
        $signature = $this->paymentService->signature($message);

        $this->assertIsString($signature);
    }

    /**
     * @test
     */
    public function testPayRequestUrl(): void
    {
        $requestData = $this->paymentService->createDataPayment($this->order, $this->customer);
        $payRequestUrl = $this->paymentService->payRequestUrl($requestData);

        $this->assertIsString($payRequestUrl);
    }

    /**
     * @test
     */
    public function testDecrypt(): void
    {
        $encrypt_message = $this->paymentService->encrypt(json_encode($this->response_message));
        $decrypt_message = $this->paymentService->decrypt($encrypt_message);

        $this->assertJson($decrypt_message);
        $this->assertEquals($this->response_message, json_decode($decrypt_message, true));
    }

    /**
     * @test
     */
    public function it_can_be_decrypt_false_encrypted_message(): void
    {
        $encrypt_message = $this->paymentService->encrypt(json_encode($this->response_message), 'invalid');
        $decrypt_message = $this->paymentService->decrypt($encrypt_message);

        $this->assertFalse($decrypt_message);
    }

    /**
     * @test
     */
    public function testCheckPaymentOrder(): void
    {
        $orderId = 'INVOICE-229396278';
        $marketPlace = '47607';
        $response = $this->paymentService->checkPaymentOrder($orderId, $marketPlace, true, true);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertJson($response->body());
    }

    /**
     * @test
     */
    public function testCheckPaymentOrderCallable(): void
    {
        $orderId = 'INVOICE-229396278';
        $marketPlace = '47607';

        $response = $this->paymentService->checkPaymentOrderCallable($orderId, function (Response $response) {
            $json = $response->body();
            $array = $response->json();

            $this->assertJson($json);
            $this->assertIsArray($array);

            return $response;
        }, $marketPlace, true, true);

        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     */
    public function testGetPayments(): void
    {
        $marketPlace = '47607';
        $response = $this->paymentService->getPayments($marketPlace);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertJson($response->body());
    }

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

        $this->response_message = [
            'payments' => [
                [
                    'orderId' => '1585687620',
                    'showOrderId' => '1585687620',
                    'paymentId' => '245215353',
                    'account' => '9046100317',
                    'amount' => 12900.00,
                    'state' => 'exp',
                    'marketPlace' => 212036621,
                    'paymentMethod' => 'ac',
                    'stateDate' => '2020-04-01T00:52:57.268+03:00',
                ],
            ],
        ];
    }
}
