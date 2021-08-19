<?php

namespace Fh\PaymentManager\Tests\Factories;

use Fh\PaymentManager\Entities\Customer;
use Fh\PaymentManager\Facades\CustomerFactoryFacade as CustomerFactory;
use Fh\PaymentManager\Tests\Fixtures\People;
use Fh\PaymentManager\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerFactoryTest extends TestCase
{
    use RefreshDatabase;

    const TEST_PHONE = '+7(666)123-45-67';
    const TEST_EMAIL = 'test@test.tt';

    /**
     * @var array
     */
    private $attributes;

    /**
     * @test
     */
    public function it_can_be_create_customer_from_payable(): void
    {
        $people = new People(self::TEST_PHONE, self::TEST_EMAIL);

        $customer = CustomerFactory::defineCustomer($people);

        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertDatabaseCount('purchase_customers', 1);
        $this->assertDatabaseHas('purchase_customers', $this->attributes);
    }

    /**
     * @test
     */
    public function it_can_be_create_customer_from_array()
    {
        $customer = CustomerFactory::defineCustomer($this->attributes);

        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertDatabaseCount('purchase_customers', 1);
        $this->assertDatabaseHas('purchase_customers', $this->attributes);
    }

    /**
     * @test
     */
    public function it_can_run_exception_if_create_customer_invalid_data(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        CustomerFactory::defineCustomer('user');
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->attributes = [
            'account' => phone_digits(self::TEST_PHONE),
            'phone' => self::TEST_PHONE,
            'email' => self::TEST_EMAIL
        ];
    }
}
