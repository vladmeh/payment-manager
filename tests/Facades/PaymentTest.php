<?php

namespace Fh\PaymentManager\Tests\Facades;

use Fh\PaymentManager\Contracts\PaymentSystem;
use Fh\PaymentManager\Facades\Payment;
use Fh\PaymentManager\Pscb\PscbPaymentSystem;
use Fh\PaymentManager\Queries\PaymentQuery;
use Fh\PaymentManager\Requests\PaymentRequestHandler;
use Fh\PaymentManager\Tests\TestCase;

class PaymentTest extends TestCase
{

    public function testRequest()
    {
        $request = Payment::requestHandler();

        $this->assertInstanceOf(PaymentRequestHandler::class, $request);
    }

    public function testSystem()
    {
        $system = Payment::system();

        $this->assertInstanceOf(PaymentSystem::class, $system);
    }

    public function testSystemByName()
    {
        $system = Payment::system('pscb');

        $this->assertInstanceOf(PaymentSystem::class, $system);
        $this->assertInstanceOf(PscbPaymentSystem::class, $system);
    }

    public function testSystemByNameException()
    {
        $this->expectException(\InvalidArgumentException::class);
        Payment::system('invalid');
    }

    public function testQuery()
    {
        $query = Payment::query();

        $this->assertInstanceOf(PaymentQuery::class, $query);
    }
}
