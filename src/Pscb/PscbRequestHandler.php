<?php

declare(strict_types=1);

namespace Fh\PaymentManager\Pscb;

use Fh\PaymentManager\Contracts\RequestHandler;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class PscbRequestHandler implements RequestHandler
{
    use Signature;

    /**
     * @var string
     */
    private $marketPlace;

    /**
     * @var string
     */
    private $url;

    /**
     * @var array
     */
    private $requestParams;

    /**
     * @return Response
     */
    public function send(): Response
    {
        $this->validateRequestParams();

        return Http::baseUrl(config('payment.pscb.merchantApiUrl'))
            ->withHeaders([
                'signature' => $this->signature(json_encode($this->requestParams))
            ])
            ->withBody(json_encode($this->requestParams), 'application/json')
            ->post($this->url);
    }

    private function validateRequestParams()
    {
        if (!Arr::has($this->requestParams, 'marketPlace')) {
            $this->requestParams['marketPlace'] = $this->getMarketPlace();
        }
    }

    /**
     * @return string
     */
    public function getMarketPlace(): string
    {
        return $this->marketPlace ?? config('payment.pscb.marketPlace');
    }

    /**
     * @param string|null $marketPlace
     */
    public function setMarketPlace(?string $marketPlace): void
    {
        $this->marketPlace = $marketPlace ?? config('payment.pscb.marketPlace');
    }

    /**
     * @param string|null $marketPlace Уникальный идентификатор Магазина в Системе. Если не передан, значение параметра будет взято из настроек сервиса
     * @param \DateTime|null $dateFrom Нижняя граница выборки (включительно). По умолчанию месяц.
     * @param \DateTime|null $dateTo Верхняя граница выборки (исключительно).
     * @param string $merchant ID Мерчанта. Если параметр передан, будет запрошен список платежей по всем Магазинам Мерчанта.
     * @param string $selectMode Тип выборки. Возможные значения: paid – завершённые платежи (значение по умолчанию), created – созданные платежи.
     * @param string $paymentType Условие выборки по типу платежа. Возможные значения: payment - все платежи, исключая счета, invoice - только счета. Если не передан или пустой, вернутся все платежи, включая счета.
     * @param bool $requestFiscalData Флаг запроса информации о фискальных документах.
     * @param bool $requestClearingData Флаг запроса информации для сверки.
     *
     * @return PscbRequestHandler
     */
    public function getPayments(
        string    $marketPlace = null,
        \DateTime $dateFrom = null,
        \DateTime $dateTo = null,
        string    $merchant = '',
        string    $selectMode = 'paid',
        string    $paymentType = '',
        bool      $requestFiscalData = false,
        bool      $requestClearingData = false
    ): PscbRequestHandler
    {
        $this->setMarketPlace($marketPlace);
        $dateFrom = $dateFrom ?? Carbon::now()->subMonth();

        return $this->createRequest('getPayments',
            array_filter(compact(
                'marketPlace',
                'dateFrom',
                'dateTo',
                'merchant',
                'selectMode',
                'paymentType',
                'requestFiscalData',
                'requestClearingData'
            )));
    }

    /**
     * @param string $url
     * @param array $params
     * @return PscbRequestHandler
     */
    public function createRequest(string $url, array $params = []): PscbRequestHandler
    {
        $this->requestParams = $params;
        $this->url = $url;

        return $this;
    }

    /**
     * @param string|null $orderId Уникальный идентификатор платежа на стороне Мерчанта (магазина), для которого запрашивается действие.
     * @param string|null $marketPlace Идентификатор Магазина, которому принадлежит orderId.
     * @param bool $requestCardData Флаг запроса расширенной информации о платеже банковской картой.
     * @param bool $requestFiscalData Флаг запроса информации о чеках, связанных с платежом.
     *
     * @return PscbRequestHandler
     */
    public function checkPayment(string $orderId,
                                 string $marketPlace = null,
                                 bool   $requestCardData = false,
                                 bool   $requestFiscalData = false): PscbRequestHandler
    {
        $this->setMarketPlace($marketPlace);

        return $this->createRequest('checkPayment',
            array_filter(compact(
                'orderId',
                'marketPlace',
                'requestCardData',
                'requestFiscalData'
            )));
    }
}
