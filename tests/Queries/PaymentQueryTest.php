<?php

namespace Fh\PaymentManager\Tests\Queries;

use Fh\PaymentManager\Contracts\QueryBuilder;
use Fh\PaymentManager\Facades\Payment;
use Fh\PaymentManager\Pscb\PscbQueryBuilder;
use Fh\PaymentManager\Queries\PaymentQuery;
use Fh\PaymentManager\Tests\TestCase;

class PaymentQueryTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_create_with_config_payment_system(): void
    {
        $query = Payment::query()->create();

        $this->assertInstanceOf(PaymentQuery::class, $query);
        $this->assertInstanceOf(QueryBuilder::class, $query->queryBuilder());
    }

    /**
     * @test
     */
    public function it_can_be_create_with_params(): void
    {
        $query = Payment::query()->create(function (QueryBuilder $builder) {
            $builder->amount(100.00);
            $builder->orderId('123');
        });

        $this->assertIsArray($query->toArray());
        $this->assertArrayHasKey('amount', $query->toArray());
        $this->assertArrayHasKey('orderId', $query->toArray());

        $this->assertJson($query->toJson());
    }

    /**
     * @test
     */
    public function it_can_be_get_pay_url(): void
    {
        $url = Payment::query()->create(function (QueryBuilder $builder) {
            $builder->amount(100.00);
            $builder->orderId('123');
        })->getPayUrl();

        $this->assertIsString($url);
        $this->assertIsUrl($url);
    }

    /**
     * @test
     */
    public function it_can_be_get_pay_url_with_market_place(): void
    {
        $marketPlace = '1234567890';

        $url = Payment::query()->create(function (QueryBuilder $builder) {
            $builder->amount(100.00);
            $builder->orderId('123');
        })->getPayUrl($marketPlace);

        $this->assertIsString($url);
        $this->assertIsUrl($url);
    }

    /**
     * @test
     */
    public function it_can_be_create_with_name_payment_system(): void
    {
        $query = Payment::system('pscb')->createQuery();

        $this->assertInstanceOf(PaymentQuery::class, $query);
        $this->assertInstanceOf(QueryBuilder::class, $query->queryBuilder());
        $this->assertInstanceOf(PscbQueryBuilder::class, $query->queryBuilder());
    }

    /**
     * @test
     */
    public function it_can_be_get_query_builder(): void
    {
        $builder = Payment::query()->queryBuilder();

        $this->assertInstanceOf(QueryBuilder::class, $builder);
    }
}
