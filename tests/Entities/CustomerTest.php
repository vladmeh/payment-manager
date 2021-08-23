<?php

namespace Fh\PaymentManager\Tests\Entities;

use Fh\PaymentManager\Entities\Customer;
use Fh\PaymentManager\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_be_creat_or_get_first_customer(): void
    {
        $phone = '+7(666)123-45-67';
        $email = 'test@test.tt';

        $customer = Customer::firstOrCreate([
            'account' => phone_digits($phone)
        ], [
            'phone' => $phone,
            'email' => $email
        ]);

        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertDatabaseCount('purchase_customers', 1);
        $this->assertDatabaseHas('purchase_customers', [
            'account' => phone_digits($phone),
            'phone' => $phone,
            'email' => $email
        ]);

        $this->assertArrayNotHasKey('created_at', $customer->toArray());
        $this->assertArrayNotHasKey('updated_at', $customer->toArray());
    }
}
