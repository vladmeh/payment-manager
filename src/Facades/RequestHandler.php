<?php

namespace Fh\PaymentManager\Facades;

use Fh\PaymentManager\Requests\PaymentRequestHandler;
use Illuminate\Support\Facades\Facade;

/**
 * @method static RequestHandler create(string $url, string[] $params = [])
 */
class RequestHandler extends Facade
{
    /**
     * @param string $name
     * @return PaymentRequestHandler
     */
    public static function paymentSystem(string $name): PaymentRequestHandler
    {
        return static::$app['payment']->paymentSystem($name)->createRequestHandler();
    }

    /**
     * @return PaymentRequestHandler
     */
    protected static function getFacadeAccessor(): PaymentRequestHandler
    {
        return static::$app['payment.system']->createRequestHandler();
    }
}
