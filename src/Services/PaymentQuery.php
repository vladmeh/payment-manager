<?php

namespace Fh\PaymentManager\Services;

use Fh\PaymentManager\Payments\QueryBuilder;
use Fh\PaymentManager\Pscb\PscbQueryBuilder;

class PaymentQuery
{
    /**
     * @param $paymentSystem
     * @param \Closure|null $callback
     * @return QueryBuilder
     */
    public function create($paymentSystem, \Closure $callback = null): QueryBuilder
    {
        return tap($this->getQueryBuilder($paymentSystem), function (QueryBuilder $builder) use ($callback) {
            $callback && $callback($builder);
        });
    }

    /**
     * @param string $paymentSystem
     * @return QueryBuilder
     *
     * @todo Move to PaymentServiceProvider
     * @todo Create an custom exception
     */
    private function getQueryBuilder(string $paymentSystem = ''): QueryBuilder
    {
        $name = $paymentSystem ?: config('payment.system');

        if ($name === 'pscb') {
            return new PscbQueryBuilder;
        }

        throw new \InvalidArgumentException("Платежная система $paymentSystem не найдена.");
    }
}
