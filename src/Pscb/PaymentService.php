<?php

declare(strict_types=1);

namespace Fh\PaymentManager\Pscb;

use DateTime;
use Fh\PaymentManager\Payments\PaymentQuery;
use Fh\PaymentManager\Payments\PaymentSystem;
use Fh\PaymentManager\Payments\QueryBuilder;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Carbon;

class PaymentService implements PaymentSystem
{
    /**
     * @param string $orderId Уникальный идентификатор платежа на стороне Мерчанта (магазина), для которого запрашивается действие.
     * @param \Closure $callback Функция обратного вызова, позволяющая динамически обрабатывать полученный ответ
     * @param mixed ...$arguments
     * @return mixed
     * @see checkPaymentOrder()
     */
    public function checkPaymentOrderCallable(string $orderId,
                                              \Closure $callback,
                                              ...$arguments
    )
    {
        $response = $this->checkPaymentOrder($orderId, ...$arguments);

        return $callback($response);
    }

    /**
     * @param string $orderId Уникальный идентификатор платежа на стороне Мерчанта (магазина), для которого запрашивается действие.
     * @param string|null $marketPlace Идентификатор Магазина, которому принадлежит orderId.
     * @param bool $requestCardData Флаг запроса расширенной информации о платеже банковской картой.
     * @param bool $requestFiscalData Флаг запроса информации о чеках, связанных с платежом.
     *
     * @return Response
     */
    public function checkPaymentOrder(string $orderId,
                                      string $marketPlace = null,
                                      bool $requestCardData = false,
                                      bool $requestFiscalData = false
    ): Response
    {
        $marketPlace = $marketPlace ?? config('payment.pscb.marketPlace');

        $messageData = compact('orderId', 'marketPlace', 'requestCardData', 'requestFiscalData');
        $messageText = json_encode($messageData);

        return PaymentRequest::send('checkPayment', $messageText);
    }

    /**
     * @param string|null $marketPlace Уникальный идентификатор Магазина в Системе. Если не передан, значение параметра будет взято из настроек сервиса
     * @param DateTime|null $dateFrom Нижняя граница выборки (включительно). По умолчанию месяц.
     * @param DateTime|null $dateTo Верхняя граница выборки (исключительно).
     * @param string $merchant ID Мерчанта. Если параметр передан, будет запрошен список платежей по всем Магазинам Мерчанта.
     * @param string $selectMode Тип выборки. Возможные значения: paid – завершённые платежи (значение по умолчанию), created – созданные платежи.
     *
     * @return Response
     */
    public function getPayments(string   $marketPlace = null,
                                DateTime $dateFrom = null,
                                DateTime $dateTo = null,
                                string   $merchant = '',
                                string   $selectMode = 'paid'): Response
    {
        $marketPlace = $marketPlace ?? config('payment.pscb.marketPlace');
        $dateFrom = $dateFrom ?? Carbon::now()->subMonth();

        $messageData = compact('marketPlace', 'dateFrom', 'dateTo', 'merchant', 'selectMode');
        $messageText = json_encode(array_filter($messageData));

        return PaymentRequest::send('getPayments', $messageText);
    }

    /**
     * @return PaymentQuery
     */
    public function getQuery(): PaymentQuery
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
