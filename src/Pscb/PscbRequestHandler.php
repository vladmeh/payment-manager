<?php

declare(strict_types=1);

namespace Fh\PaymentManager\Pscb;

use Fh\PaymentManager\Contracts\RequestHandler;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class PscbRequestHandler implements RequestHandler
{
    use Signature;

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
        $signature = $this->signature(json_encode($this->requestParams));

        return Http::baseUrl(config('payment.pscb.merchantApiUrl'))
            ->withHeaders([
                'signature' => $signature
            ])
            ->withBody(json_encode($this->requestParams), 'application/json')
            ->post($this->url);
    }

    /**
     * @param string|null $marketPlace Уникальный идентификатор Магазина в Системе. Если не передан, значение параметра будет взято из настроек сервиса
     * @param \DateTime|null $dateFrom Нижняя граница выборки (включительно). По умолчанию месяц.
     * @param \DateTime|null $dateTo Верхняя граница выборки (исключительно).
     * @param string $merchant ID Мерчанта. Если параметр передан, будет запрошен список платежей по всем Магазинам Мерчанта.
     * @param string $selectMode Тип выборки. Возможные значения: paid – завершённые платежи (значение по умолчанию), created – созданные платежи.
     *
     * @return PscbRequestHandler
     */
    public function getPayments(string    $marketPlace = null,
                                \DateTime $dateFrom = null,
                                \DateTime $dateTo = null,
                                string    $merchant = '',
                                string    $selectMode = 'paid'): PscbRequestHandler
    {
        $marketPlace = $this->getMarketPlace($marketPlace);

        $dateFrom = $dateFrom ?? Carbon::now()->subMonth()->toDateString();

        return $this->createRequest('getPayments',
            array_filter(compact(
                'marketPlace',
                'dateFrom',
                'dateTo',
                'merchant',
                'selectMode'
            )));
    }

    /**
     * @param string|null $marketPlace
     * @return string
     */
    private function getMarketPlace(?string $marketPlace): string
    {
        return $marketPlace ?? config('payment.pscb.marketPlace');
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
        $marketPlace = $this->getMarketPlace($marketPlace);

        return $this->createRequest('checkPayment',
            array_filter(compact(
                'orderId',
                'marketPlace',
                'requestCardData',
                'requestFiscalData'
            )));
    }

}
