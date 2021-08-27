<?php

namespace Fh\PaymentManager\Payments;

class PaymentQuery
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
     * @return QueryBuilder
     */
    public function create(\Closure $callback = null): QueryBuilder
    {
        return tap($this->getQueryBuilder(), function (QueryBuilder $builder) use ($callback) {
            $callback && $callback($builder);
        });
    }

    /**
     * @return QueryBuilder
     */
    private function getQueryBuilder(): QueryBuilder
    {
        return $this->queryBuilder;
    }
}
