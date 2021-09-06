<?php

namespace Fh\PaymentManager\Tests\Requests;

use Fh\PaymentManager\Contracts\RequestHandler;
use Fh\PaymentManager\Facades\Payment;
use Fh\PaymentManager\Pscb\PscbRequestHandler;
use Fh\PaymentManager\Requests\PaymentRequestHandler;
use Fh\PaymentManager\Tests\TestCase;
use Illuminate\Support\Facades\Http;

class PaymentRequestHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_create_with_config_payment_system(): void
    {
        $requestHandler = Payment::requestHandler()->create('test', ['param' => 'test']);

        $this->assertInstanceOf(PaymentRequestHandler::class, $requestHandler);
    }

    /**
     * @test
     */
    public function it_can_be_create_with_name_payment_system(): void
    {
        $requestHandler = Payment::system('pscb')->requestHandler()->create('test', ['param' => 'test']);

        $this->assertInstanceOf(PaymentRequestHandler::class, $requestHandler);
        $this->assertInstanceOf(RequestHandler::class, $requestHandler->requestHandler());
        $this->assertInstanceOf(PscbRequestHandler::class, $requestHandler->requestHandler());
    }

    /**
     * @test
     */
    public function it_can_be_send(): void
    {
        Http::fake();

        $requestHandler = Payment::system('pscb')->requestHandler()->create('test', ['param' => 'test']);
        $requestHandler->send();

        Http::assertSent(function ($request) {
            return $request->body() === json_encode(['param' => 'test', 'marketPlace' => config('payment.pscb.marketPlace')]);
        });
    }
}
