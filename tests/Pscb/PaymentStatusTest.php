<?php

namespace Vladmeh\PaymentManager\Tests\Pscb;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Vladmeh\PaymentManager\Models\Order;
use Vladmeh\PaymentManager\Pscb\PaymentStatus;
use Vladmeh\PaymentManager\Tests\TestCase;

class PaymentStatusTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_be_set_status_default(): void
    {
        $order = factory(Order::class)->create();
        $this->assertEquals(PaymentStatus::UNDEF, $order->state);
    }

    /**
     * @test
     */
    public function testStatusIsConstants()
    {
        $payment = factory(Order::class)->create([
            'state' => PaymentStatus::status('end')
        ]);

        $this->assertEquals(PaymentStatus::END, $payment->state);
    }

    /**
     * @test
     */
    public function testStatusIsNotConstants(): void
    {
        $payment = factory(Order::class)->create([
            'state' => PaymentStatus::status('undefined')
        ]);

        $this->assertNotEquals(PaymentStatus::UNDEF, $payment->state);
    }

    /**
     * @test
     */
    public function testIsFinalState()
    {
        $payment = factory(Order::class)->create([
            'state' => PaymentStatus::END
        ]);

        $this->assertTrue(PaymentStatus::isFinalState($payment->state));
    }

    /**
     * @test
     */
    public function testIsNotFinalState()
    {
        $payment = factory(Order::class)->create([
            'state' => PaymentStatus::SENT
        ]);

        $this->assertFalse(PaymentStatus::isFinalState($payment->state));
    }
}
