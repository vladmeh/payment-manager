<?php

namespace Vladmeh\PaymentManager\Tests\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Vladmeh\PaymentManager\Models\Payment;
use Vladmeh\PaymentManager\Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_be_created_payment(): void
    {
        $payment = factory(Payment::class)->create();
        $this->assertInstanceOf(Payment::class, $payment);
        $this->assertDatabaseCount('payments', 1);
    }

}
