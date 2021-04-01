<?php

namespace Fh\PaymentManager\Tests\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Fh\PaymentManager\Contracts\PayableCustomer;
use Fh\PaymentManager\Models\PaymentCustomer;
use Fh\PaymentManager\Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_be_created_customer(): void
    {
        $customer = factory(PaymentCustomer::class)->create();

        $this->assertInstanceOf(PayableCustomer::class, $customer);
        $this->assertDatabaseHas('customers', $customer->toArray());
    }

    /**
     * @test
     */
    public function it_can_be_get_account(): void
    {
        $customer = factory(PaymentCustomer::class)->create();

        $this->assertEquals($customer->account, $customer->getAccount());
    }

    /**
     * @test
     */
    public function it_can_be_get_email(): void
    {
        $customer = factory(PaymentCustomer::class)->create();

        $this->assertEquals($customer->email, $customer->getEmail());
    }

    /**
     * @test
     */
    public function it_can_be_get_phone(): void
    {
        $customer = factory(PaymentCustomer::class)->create();

        $this->assertEquals($customer->phone, $customer->getPhone());
    }

    /**
     * @test
     */
    public function it_can_be_get_comment(): void
    {
        $customer = factory(PaymentCustomer::class)->create();

        $this->assertEquals($customer->comment, $customer->getComment());
    }
}
