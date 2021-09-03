<?php

namespace Fh\PaymentManager\Queries;

use Fh\PaymentManager\Contracts\QueryBuilder;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class PaymentQuery implements Arrayable, Jsonable
{
    /**
     * @var QueryBuilder
     */
    private $queryBuilder;

    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * @param \Closure|null $callback
     * @return PaymentQuery
     */
    public function create(\Closure $callback = null): PaymentQuery
    {
        return $this->build(tap($this->queryBuilder(), function (QueryBuilder $builder) use ($callback) {
            $callback && $callback($builder);
        }));
    }

    /**
     * @param QueryBuilder $builder
     * @return PaymentQuery
     */
    private function build(QueryBuilder $builder): PaymentQuery
    {
        $this->queryBuilder = $builder;

        return $this;
    }

    /**
     * @return QueryBuilder
     */
    public function queryBuilder(): QueryBuilder
    {
        return $this->queryBuilder;
    }

    /**
     * @return string
     */
    public function getPayUrl(): string
    {
        return $this->queryBuilder->getPayUrl();
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->queryBuilder->toArray();
    }

    /**
     * @param int $options
     * @return string
     */
    public function toJson($options = 0): string
    {
        return $this->queryBuilder->toJson($options);
    }
}
