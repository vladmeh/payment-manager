<?php

namespace Fh\PaymentManager\Tests\Payments;

use Fh\PaymentManager\Payments\PaymentSystem;
use Fh\PaymentManager\Pscb\PaymentService;
use Fh\PaymentManager\Tests\TestCase;

class PaymentManagerTest extends TestCase
{

    public function testGetDefaultPaymentSystem()
    {
        $name = $this->app['payment']->getDefaultPaymentSystem();

        $this->assertEquals(config('payment.system'), $name);
    }

    public function testPaymentSystem()
    {
        $system = $this->app['payment']->paymentSystem();

        $this->assertInstanceOf(PaymentSystem::class, $system);
    }

    /**
     * @test
     */
    public function it_can_be_get_payment_system_by_name(): void
    {
        $system = $this->app['payment']->paymentSystem('pscb');

        $this->assertInstanceOf(PaymentSystem::class, $system);
        $this->assertInstanceOf(PaymentService::class, $system);
    }

    /**
     * @test
     */
    public function it_can_be_exception_if_unsupported_system(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->app['payment']->paymentSystem('invalid');
    }
}
