<?php

namespace Fh\PaymentManager\Tests\Pscb;

use Fh\PaymentManager\Pscb\PscbRequestHandler;
use Fh\PaymentManager\Tests\TestCase;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class PscbRequestHandlerTest extends TestCase
{
    const ORDER_ID = 'INVOICE-229396278';
    const MARKET_PLACE = '47607';
    const AMOUNT = 100.00;


    public function testCreateRequest()
    {
        Http::fake();

        $requestParams = [
            "order" => self::ORDER_ID,
            "market" => self::MARKET_PLACE,
            "amount" => self::AMOUNT
        ];

        $url = "test";

        $requestHandler = (new PscbRequestHandler)->createRequest($url, $requestParams);

        $response = $requestHandler->send();

        $this->assertInstanceOf(Response::class, $response);

        Http::assertSent(function ($request) use ($url, $requestParams) {
            return $request->url() === config('payment.pscb.merchantApiUrl') . $url &&
                $request->body() === json_encode($requestParams);
        });
    }

    public function testGetPayments()
    {
        Http::fake();

        $requestHandler = (new PscbRequestHandler)->getPayments();
        $response = $requestHandler->send();

        $this->assertInstanceOf(Response::class, $response);

        Http::assertSent(function ($request) {
            return $request->url() === config('payment.pscb.merchantApiUrl') . 'getPayments' &&
                $request->body() === json_encode([
                    "marketPlace" => config('payment.pscb.marketPlace'),
                    "dateFrom" => Carbon::now()->subMonth()->toDateString(),
                    "selectMode" => "paid"
                ]);
        });
    }

    public function testCheckPayment()
    {
        Http::fake();

        $requestHandler = (new PscbRequestHandler)->checkPayment(self::ORDER_ID);
        $response = $requestHandler->send();

        $this->assertInstanceOf(Response::class, $response);

        Http::assertSent(function ($request) {
            return $request->url() === config('payment.pscb.merchantApiUrl') . 'checkPayment' &&
                $request->body() === json_encode([
                    "orderId" => self::ORDER_ID,
                    "marketPlace" => config('payment.pscb.marketPlace'),
                ]);
        });
    }
}
