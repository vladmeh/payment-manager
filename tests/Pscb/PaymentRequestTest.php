<?php

namespace Fh\PaymentManager\Tests\Pscb;

use Fh\PaymentManager\Models\PaymentCustomer;
use Fh\PaymentManager\Models\PaymentOrder;
use Fh\PaymentManager\Pscb\OrderPaymentRequest;
use Fh\PaymentManager\Pscb\PaymentService;
use Fh\PaymentManager\Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Response;

class PaymentRequestTest extends TestCase
{
    /**
     * @var OrderPaymentRequest
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
        $this->paymentRequest = new OrderPaymentRequest;
        $this->paymentService = new PaymentService($this->paymentRequest);
        $this->order = factory(PaymentOrder::class)->make([
            'amount' => 200,
            'details' => 'Тестовая услуга'
        ]);

        $this->customer = factory(PaymentCustomer::class)->make([
            'account' => '1234567890',
            'email' => 'customer@mail.test',
            'phone' => '+7(123)456-78-90',
            'comment' => 'Comment'
        ]);
    }
}
