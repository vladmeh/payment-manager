<?php

namespace Fh\PaymentManager\Tests\Requests;

use Fh\PaymentManager\Contracts\RequestHandler as RequestHandlerInterface;
use Fh\PaymentManager\Facades\RequestHandler;
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
        $requestHandler = RequestHandler::create('test', ['param' => 'test']);

        $this->assertInstanceOf(PaymentRequestHandler::class, $requestHandler);
    }

    /**
     * @test
     */
    public function it_can_be_create_with_name_payment_system(): void
    {
        $requestHandler = RequestHandler::paymentSystem('pscb')->create('test', ['param' => 'test']);

        $this->assertInstanceOf(PaymentRequestHandler::class, $requestHandler);
        $this->assertInstanceOf(RequestHandlerInterface::class, $requestHandler->requestHandler());
        $this->assertInstanceOf(PscbRequestHandler::class, $requestHandler->requestHandler());
    }

    /**
     * @test
     */
    public function it_can_be_create_with_name_payment_system_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        RequestHandler::paymentSystem('invalid');
    }

    /**
     * @test
     */
    public function it_can_be_get_request_handler(): void
    {
        $requestHandler = RequestHandler::requestHandler();

        $this->assertInstanceOf(RequestHandlerInterface::class, $requestHandler);
    }

    /**
     * @test
     */
    public function it_can_be_send(): void
    {
        Http::fake();

        $requestHandler = RequestHandler::paymentSystem('pscb')->create('test', ['param' => 'test']);
        $requestHandler->send();

        Http::assertSent(function ($request) {
            return $request->body() === json_encode(['param' => 'test', 'marketPlace' => config('payment.pscb.marketPlace')]);
        });
    }
}
