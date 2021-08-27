<?php

namespace Fh\PaymentManager\Tests\Pscb;

use Fh\PaymentManager\Models\PaymentCustomer;
use Fh\PaymentManager\Models\PaymentOrder;
use Fh\PaymentManager\Pscb\NotificationRequest;
use Fh\PaymentManager\Pscb\PaymentStatus;
use Fh\PaymentManager\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class NotificationRequestTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @var array
     */
    private $message;

    /**
     * @var array
     */
    private $responseData;

    /**
     * @test
     */
    public function test_make_custom_request(): void
    {
        $request = NotificationRequest::create('');
        $this->assertInstanceOf(NotificationRequest::class, $request);
        $this->assertInstanceOf(Request::class, $request);
    }

    /**
     * @test
     * @throws ValidationException
     */
    public function it_can_be_valid_request_data(): void
    {
        $request = NotificationRequest::create('', 'POST', $this->message);
        $request->validateDate();
        $this->expectNotToPerformAssertions();
    }

    /**
     * @test
     * @throws ValidationException
     */
    public function it_can_be_invalid_request_data(): void
    {
        $this->expectException(ValidationException::class);
        $request = NotificationRequest::create('', 'POST', ['message' => 'invalid']);
        $request->validateDate();
    }

    /**
     * @test
     */
    public function it_can_be_get_response_data(): void
    {
        $request = NotificationRequest::create('', 'POST', $this->message);
        $payments = $request->responseData();

        $this->assertIsArray($payments);
        $this->assertEquals($this->responseData, compact('payments'));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $customer = factory(PaymentCustomer::class)->create();
        $orders = factory(PaymentOrder::class, 3)->create(
            ['customer_id' => $customer->id]
        );

        $this->message = [
            'payments' => [
                [
                    'orderId' => $orders->first()->uuid,
                    'showOrderId' => '1585687620',
                    'paymentId' => '245215353',
                    'account' => $orders->first()->customer->account,
                    'amount' => $orders->first()->amount,
                    'state' => 'exp',
                    'marketPlace' => '328150779',
                    'paymentMethod' => 'ac',
                    'stateDate' => '2020-04-01T00:52:57.268+03:00'
                ],
                [
                    'orderId' => $orders->last()->uuid,
                    'showOrderId' => '1585687620',
                    'paymentId' => '245215354',
                    'account' => 'undefined',
                    'amount' => $orders->last()->amount,
                    'state' => 'exp',
                    'marketPlace' => '328150779',
                    'paymentMethod' => 'ac',
                    'stateDate' => '2020-04-01T00:52:57.268+03:00'
                ],
                [
                    'orderId' => $this->faker->uuid,
                    'showOrderId' => '1585687620',
                    'paymentId' => '245215355',
                    'account' => '1234567890',
                    'amount' => 100.00,
                    'state' => 'exp',
                    'marketPlace' => '328150779',
                    'paymentMethod' => 'ac',
                    'stateDate' => '2020-04-01T00:52:57.268+03:00'
                ],
            ]
        ];

        $this->responseData = [
            'payments' => [
                [
                    'orderId' => $this->message['payments'][0]['orderId'],
                    'action' => PaymentStatus::ACTION_CONFIRM
                ],
                [
                    'orderId' => $this->message['payments'][1]['orderId'],
                    'action' => PaymentStatus::ACTION_REJECT
                ],
                [
                    'orderId' => $this->message['payments'][2]['orderId'],
                    'action' => PaymentStatus::ACTION_REJECT
                ],
            ]
        ];
    }
}
