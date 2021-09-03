<?php

declare(strict_types=1);

namespace Fh\PaymentManager\Pscb;

use Fh\PaymentManager\Contracts\PaymentSystem;
use Fh\PaymentManager\Contracts\QueryBuilder;
use Fh\PaymentManager\Contracts\RequestHandler;
use Fh\PaymentManager\Queries\PaymentQuery;
use Fh\PaymentManager\Requests\PaymentRequestHandler;

class PaymentService implements PaymentSystem
{
    /**
     * @return PaymentRequestHandler
     */
    public function createRequestHandler(): PaymentRequestHandler
    {
        return new PaymentRequestHandler($this->getRequestHandler());
    }

    /**
     * @return RequestHandler
     */
    private function getRequestHandler(): RequestHandler
    {
        return new PscbRequestHandler();
    }

    /**
     * @return PaymentQuery
     */
    public function createQuery(): PaymentQuery
    {
        return new PaymentQuery($this->getQueryBuilder());
    }

    /**
     * @return QueryBuilder
     */
    private function getQueryBuilder(): QueryBuilder
    {
        return new PscbQueryBuilder();
    }
}
