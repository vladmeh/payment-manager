<?php

namespace Vladmeh\PaymentManager\Tests\Pscb;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Response;
use Vladmeh\PaymentManager\Models\Customer;
use Vladmeh\PaymentManager\Models\Order;
use Vladmeh\PaymentManager\Pscb\PaymentRequest;
use Vladmeh\PaymentManager\Pscb\PaymentService;
use Vladmeh\PaymentManager\Tests\TestCase;

class PaymentRequestTest extends TestCase
{
    /**
     * @var PaymentRequest
     */
    private $paymentRequest;
    /**
     * @var PaymentService
     */
    private $paymentService;
    /**
     * @var Collection|Model|mixed
     */
    private $order;
    /**
     * @var Collection|Model|mixed
     */
    private $customer;

    public function testSignature()
    {
        $requestData = $this->paymentService->createMessageRequest($this->order, $this->customer);
        $message = $requestData->toJson();
        $signature = $this->paymentRequest->signature($message);

        $this->assertIsString($signature);
    }

    public function testSendMessage()
    {
        $orderId = 'INVOICE-229396278';
        $marketPlace = '47607';

        $messageData = compact('orderId', 'marketPlace');
        $messageText = json_encode($messageData);

        $response = $this->paymentRequest->sendMessage('checkPayment', $messageText);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertJson($response->body());
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->paymentRequest = new PaymentRequest;
        $this->paymentService = new PaymentService($this->paymentRequest);
        $this->order = factory(Order::class)->make([
            'amount' => 200,
            'details' => 'Тестовая услуга'
        ]);

        $this->customer = factory(Customer::class)->make([
            'account' => '1234567890',
            'email' => 'customer@mail.test',
            'phone' => '+7(123)456-78-90',
            'comment' => 'Comment'
        ]);
    }
}
