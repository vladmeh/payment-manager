<?php

namespace Fh\PaymentManager\Services;

use Fh\PaymentManager\Payments\QueryBuilder;
use Fh\PaymentManager\Pscb\PscbQueryBuilder;

class Payment
{
    /**
     * @param $paymentSystem
     * @param \Closure $callback
     * @return QueryBuilder
     */
    public function createQuery($paymentSystem, \Closure $callback): QueryBuilder
    {
        return tap($this->getQueryBuilder($paymentSystem), function (QueryBuilder $builder) use ($callback) {
            $callback($builder);
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