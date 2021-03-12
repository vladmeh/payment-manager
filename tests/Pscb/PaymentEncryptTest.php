<?php

namespace Vladmeh\PaymentManager\Tests\Pscb;

use Vladmeh\PaymentManager\Pscb\PaymentEncrypt;
use Vladmeh\PaymentManager\Tests\TestCase;

class PaymentEncryptTest extends TestCase
{
    private $response_message;

    public function testDecrypt()
    {
        $encrypt_message = PaymentEncrypt::encrypt(json_encode($this->response_message));
        $decrypt_message = PaymentEncrypt::decrypt($encrypt_message);

        $this->assertJson($decrypt_message);
        $this->assertEquals($this->response_message, json_decode($decrypt_message, true));
    }

    public function testEncrypt()
    {
        $encrypt_message = PaymentEncrypt::encrypt(json_encode($this->response_message), 'invalid');
        $decrypt_message = PaymentEncrypt::decrypt($encrypt_message);

        $this->assertFalse($decrypt_message);
    }

    protected function setUp(): void
    {
        parent::setUp();

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
