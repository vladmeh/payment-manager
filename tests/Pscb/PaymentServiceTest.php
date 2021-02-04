<?php

namespace Vladmeh\PaymentManager\Tests\Pscb;

use Vladmeh\PaymentManager\Pscb\PaymentService;
use Vladmeh\PaymentManager\Tests\TestCase;

class PaymentServiceTest extends TestCase
{
    private $paymentService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->paymentService = new PaymentService();
    }
}
