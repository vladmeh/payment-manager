<?php

namespace Fh\PaymentManager\Tests\Pscb;

use Illuminate\Http\Request;
use Fh\PaymentManager\Pscb\PaymentEncrypt;
use Fh\PaymentManager\Pscb\RequestDecryptMiddleware;
use Fh\PaymentManager\Tests\TestCase;

class RequestDecryptMiddlewareTest extends TestCase
{
    private $message;

    public function testHandle()
    {
        $encryptMessage = PaymentEncrypt::encrypt(json_encode($this->message));
        $request = Request::create('', 'POST', [], [], [], ['Content-Type' => 'application/octet-stream'], $encryptMessage);

        (new RequestDecryptMiddleware)->handle($request, function ($request) {
            $this->assertEquals('Hello World', $request->message);
        });
    }

    public function testHandleErrorResponse()
    {
        $encryptMessage = PaymentEncrypt::encrypt(json_encode($this->message), 'invalid');
        $request = Request::create('', 'POST', [], [], [], ['Content-Type' => 'application/octet-stream'], $encryptMessage);

        $response = (new RequestDecryptMiddleware)->handle($request, function ($request) {
        });
        $this->assertEquals(401, $response->status());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->message = [
            'message' => 'Hello World',
        ];
    }
}
