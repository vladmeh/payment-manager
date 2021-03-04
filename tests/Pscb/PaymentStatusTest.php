<?php

namespace Vladmeh\PaymentManager\Tests\Pscb;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Vladmeh\PaymentManager\Models\Payment;
use Vladmeh\PaymentManager\Pscb\PaymentStatus;
use Vladmeh\PaymentManager\Tests\TestCase;

class PaymentStatusTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function testStatusIsConstants()
    {
        $payment = factory(Payment::class)->create([
            'state' => PaymentStatus::status('end')
        ]);

        $this->assertEquals(PaymentStatus::END, $payment->state);
    }

    /**
     * @test
     */
    public function testStatusIsNotConstants(): void
    {
        $payment = factory(Payment::class)->create([
            'state' => PaymentStatus::status('undefined')
        ]);

        $this->assertNotEquals(PaymentStatus::UNDEF, $payment->state);
    }

    /**
     * @test
     */
    public function testIsFinalState()
    {
        $payment = factory(Payment::class)->create([
            'state' => PaymentStatus::END
        ]);

        $this->assertTrue(PaymentStatus::isFinalState($payment->state));
    }
    /**
     * @test
     */
    public function testIsNotFinalState()
    {
        $payment = factory(Payment::class)->create([
            'state' => PaymentStatus::SENT
        ]);

        $this->assertFalse(PaymentStatus::isFinalState($payment->state));
    }
}
