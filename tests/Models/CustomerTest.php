<?php

namespace Vladmeh\PaymentManager\Tests\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Vladmeh\PaymentManager\Contracts\PayableCustomer;
use Vladmeh\PaymentManager\Models\Customer;
use Vladmeh\PaymentManager\Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_be_created_customer(): void
    {
        $customer = factory(Customer::class)->create();

        $this->assertInstanceOf(PayableCustomer::class, $customer);
        $this->assertDatabaseHas('customers', $customer->toArray());
    }

    /**
     * @test
     */
    public function it_can_be_get_account(): void
    {
        $customer = factory(Customer::class)->create();

        $this->assertEquals($customer->account, $customer->getAccount());
    }

    /**
     * @test
     */
    public function it_can_be_get_email(): void
    {
        $customer = factory(Customer::class)->create();

        $this->assertEquals($customer->email, $customer->getEmail());
    }

    /**
     * @test
     */
    public function it_can_be_get_phone(): void
    {
        $customer = factory(Customer::class)->create();

        $this->assertEquals($customer->phone, $customer->getPhone());
    }

    /**
     * @test
     */
    public function it_can_be_get_comment(): void
    {
        $customer = factory(Customer::class)->create();

        $this->assertEquals($customer->comment, $customer->getComment());
    }
}
