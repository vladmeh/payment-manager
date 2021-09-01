<?php

namespace Fh\PaymentManager\Tests\Pscb;

use Fh\PaymentManager\Pscb\NotificationRequest;
use Fh\PaymentManager\Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class NotificationRequestTest extends TestCase
{
    use WithFaker;

    /**
     * @var array
     */
    private $message;

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
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->message = [
            'payments' => [
                [
                    'orderId' => $this->faker->uuid,
                    'showOrderId' => '1585687620',
                    'paymentId' => '245215353',
                    'account' => '1234567890',
                    'amount' => '100.00',
                    'state' => 'exp',
                    'marketPlace' => '328150779',
                    'paymentMethod' => 'ac',
                    'stateDate' => '2020-04-01T00:52:57.268+03:00'
                ],
                [
                    'orderId' => $this->faker->uuid,
                    'showOrderId' => '1585687620',
                    'paymentId' => '245215354',
                    'account' => 'undefined',
                    'amount' => '200',
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
    }
}
