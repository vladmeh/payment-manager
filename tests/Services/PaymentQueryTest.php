<?php

namespace Fh\PaymentManager\Tests\Services;

use Fh\PaymentManager\Facades\PaymentQueryFacade as PaymentQuery;
use Fh\PaymentManager\Payments\QueryBuilder;
use Fh\PaymentManager\Pscb\PscbQueryBuilder;
use Fh\PaymentManager\Tests\TestCase;

class PaymentQueryTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_create_with_config_payment_system(): void
    {
        $paymentSystem = config('payment.system');
        $query = PaymentQuery::create($paymentSystem);

        $this->assertInstanceOf(QueryBuilder::class, $query);
    }

    /**
     * @test
     */
    public function it_can_be_create_with_name_payment_system(): void
    {
        $query = PaymentQuery::create('pscb');

        $this->assertInstanceOf(QueryBuilder::class, $query);
        $this->assertInstanceOf(PscbQueryBuilder::class, $query);
    }

    /**
     * @test
     */
    public function it_can_be_create_with_name_payment_system_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        PaymentQuery::create('invalid');
    }
}
