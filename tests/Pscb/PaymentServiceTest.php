<?php

namespace Fh\PaymentManager\Tests\Pscb;

use Fh\PaymentManager\Models\PaymentCustomer;
use Fh\PaymentManager\Models\PaymentOrder;
use Fh\PaymentManager\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Response;

class PaymentServiceTest extends TestCase
{
    use RefreshDatabase;

    private $paymentService;

    private $order;

    private $customer;

    /**
     * @test
     */
    public function testCreatePayment(): void
    {
        $paymentRequest = $this->paymentService->createMessageRequest($this->order, $this->customer);
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

        $requestData = $this->paymentService->createMessageRequest($this->order, $this->customer, $params);
        $message = $requestData->toArray();

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
    public function testPayRequestUrl(): void
    {
        $requestData = $this->paymentService->createMessageRequest($this->order, $this->customer);
        $payRequestUrl = $this->paymentService->payRequestUrl($requestData);

        $this->assertIsString($payRequestUrl);
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
        $this->paymentService = $this->app->get('payment.pscb');

        $this->order = factory(PaymentOrder::class)->create([
            'amount' => 200,
            'details' => 'Тестовая услуга'
        ]);

        $this->customer = factory(PaymentCustomer::class)->create([
            'account' => '1234567890',
            'email' => 'customer@mail.test',
            'phone' => '+7(123)456-78-90',
            'comment' => 'Comment'
        ]);
    }
}
