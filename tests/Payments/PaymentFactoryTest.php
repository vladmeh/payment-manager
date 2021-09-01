<?php

namespace Fh\PaymentManager\Tests\Payments;

use Fh\PaymentManager\Contracts\PaymentSystem;
use Fh\PaymentManager\Factories\PaymentFactory;
use Fh\PaymentManager\Tests\TestCase;

class PaymentFactoryTest extends TestCase
{

    /**
     * @var PaymentFactory
     */
    private $factory;
    /**
     * @var array
     */
    private $config;

    public function testMake()
    {
        $system = $this->factory->make($this->config, 'pscb');

        $this->assertInstanceOf(PaymentSystem::class, $system);
    }

    /**
     * @test
     */
    public function it_can_be_exception_if_null_name(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->factory->make($this->config);
    }

    /**
     * @test
     */
    public function it_can_be_exception_if_unsupported_system(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->factory->make($this->config, 'invalid');
    }

    /**
     * @test
     */
    public function it_can_be_make_if_empty_config(): void
    {
        $system = $this->factory->make([], 'pscb');

        $this->assertInstanceOf(PaymentSystem::class, $system);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->config = config('payment.pscb');
        $this->factory = new PaymentFactory($this->app);
    }
}
