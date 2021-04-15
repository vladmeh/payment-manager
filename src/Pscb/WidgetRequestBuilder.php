<?php

declare(strict_types=1);

namespace Fh\PaymentManager\Pscb;

class WidgetRequestBuilder extends MessageRequestBuilder
{
    /**
     * Идентификатор Магазина.
     * Обязательный для виджета
     *
     * @var string
     */
    private $marketPlace;

    /**
     * WidgetRequestBuilder constructor.
     * @param int $amount
     * @param string $orderId
     * @param array $params
     */
    public function __construct(int $amount, string $orderId, array $params = [])
    {
        $this->marketPlace = config('payment.pscb.marketPlace');
        parent::__construct($amount, $orderId, $params);
    }
}
