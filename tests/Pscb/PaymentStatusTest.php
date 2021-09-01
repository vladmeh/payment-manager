<?php

namespace Fh\PaymentManager\Tests\Pscb;

use Fh\PaymentManager\Pscb\PaymentStatus;
use Fh\PaymentManager\Tests\TestCase;

class PaymentStatusTest extends TestCase
{
    /**
     * @test
     */
    public function testStatusIsConstants()
    {
        $this->assertEquals(PaymentStatus::END, PaymentStatus::status('end'));
    }

    /**
     * @test
     */
    public function testStatusIsNotConstants(): void
    {
        $this->assertNotEquals(PaymentStatus::UNDEF, PaymentStatus::status('undefined'));
    }

    /**
     * @test
     */
    public function testIsFinalState()
    {
        $this->assertTrue(PaymentStatus::isFinalState(PaymentStatus::END));
    }

    /**
     * @test
     */
    public function testIsNotFinalState()
    {
        $this->assertFalse(PaymentStatus::isFinalState(PaymentStatus::SENT));
    }
}
