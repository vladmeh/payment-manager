<?php

declare(strict_types=1);

namespace Vladmeh\PaymentManager\Pscb;

use DateTime;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Carbon;
use Vladmeh\PaymentManager\Contracts\PaymentCustomer;
use Vladmeh\PaymentManager\Contracts\PaymentOrder;

class PaymentService
{
    /** @var PaymentHandler */
    private $paymentHandler;

    public function __construct(PaymentHandler $paymentHandler)
    {
        $this->paymentHandler = $paymentHandler;
    }

    /**
     * Создание данных для запроса создания платежа в ПСКБ.
     *
     * @param PaymentOrder $order
     * @param PaymentCustomer $customer
     * @param array $requestParameters
     * @return PaymentRequestData
     */
    public function createDataPayment(PaymentOrder $order, PaymentCustomer $customer, array $requestParameters = []): PaymentRequestData
    {
        return PaymentRequestData::make($order->getAmount(), $order->getOrderId(), $requestParameters)
            ->setCustomerAccount($customer->getAccount())
            ->setCustomerEmail($customer->getEmail())
            ->setCustomerPhone($customer->getPhone())
            ->setCustomerComment($customer->getComment());
    }

    /**
     * @param string $orderId Уникальный идентификатор платежа на стороне Мерчанта (магазина), для которого запрашивается действие.
     * @param \Closure $callback Функция обратного вызова, позволяющая динамически обрабатывать полученный ответ
     * @param mixed ...$arguments
     * @return mixed
     * @see checkPaymentOrder()
     */
    public function checkPaymentOrderCallable(string $orderId, \Closure $callback, ...$arguments)
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
    public function checkPaymentOrder(string $orderId, string $marketPlace = null, bool $requestCardData = false, bool $requestFiscalData = false): Response
    {
        $marketPlace = $marketPlace ?? config('payment.pscb.marketPlace');

        $messageData = compact('orderId', 'marketPlace', 'requestCardData', 'requestFiscalData');
        $messageText = json_encode($messageData);

        return $this->request('checkPayment', $messageText);
    }

    /**
     * @param string $messageText
     * @param string $url
     *
     * @return Response
     */
    private function request(string $url, string $messageText): Response
    {
        $signature = $this->signature($messageText);

        return $this->paymentHandler->send($url, $messageText, $signature);
    }

    /**
     * Подпись сообщения с использованием ключа API secretKey.
     *
     * @param string $messageText JSON UTF8
     * @return string
     */
    final public function signature(string $messageText): string
    {
        return hash('sha256', $messageText . config('payment.pscb.secretKey'));
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
    public function getPayments(string $marketPlace = null,
                                DateTime $dateFrom = null,
                                DateTime $dateTo = null,
                                string $merchant = '',
                                string $selectMode = 'paid'): Response
    {
        $marketPlace = $marketPlace ?? config('payment.pscb.marketPlace');
        $dateFrom = $dateFrom ?? Carbon::now()->subMonth();

        $messageData = compact('marketPlace', 'dateFrom', 'dateTo', 'merchant', 'selectMode');
        $messageText = json_encode(array_filter($messageData));

        return $this->request('getPayments', $messageText);
    }

    /**
     * Формирование URL запроса создания платежа.
     *
     * @param PaymentRequestData $requestData Объект с данными для запроса создания платежа
     * @param string|null $marketPlace Идентификатор Магазина
     * @return string
     */
    public function payRequestUrl(PaymentRequestData $requestData, string $marketPlace = null): string
    {
        $request_url = config('payment.pscb.requestUrl');
        $message = $requestData->toJson();

        $params = [
            'marketPlace' => $marketPlace ?? config('payment.pscb.marketPlace'),
            'message' => base64_encode($message),
            'signature' => $this->signature($message),
        ];

        return url($request_url) . '?' . http_build_query($params);
    }

    /**
     * Функция расшифровки сообщения от ПСКБ.
     * @param $encrypted mixed зашифрованное сообщение; массив байтов
     * @param null $merchant_key string секретный ключ мерчанта; текстовая строка
     * @return mixed расшифрованное сообщение; массив байтов, одновременно - строка в кодировке UTF-8
     */
    final public function decrypt($encrypted, $merchant_key = null)
    {
        if (null == $merchant_key) {
            $merchant_key = config('payment.pscb.secretKey');
        }

        $key_md5_binary = hash('md5', $merchant_key, true);

        return openssl_decrypt($encrypted, 'AES-128-ECB', $key_md5_binary, OPENSSL_RAW_DATA);
    }

    /**
     * Функция шифровки сообщения ПСКБ.
     * @param $message string текстовое сообщение - строка в кодировке UTF-8
     * @param null $merchant_key string секретный ключ мерчанта; текстовая строка
     * @return mixed зашифрованное сообщение; массив байтов
     */
    final public function encrypt(string $message, $merchant_key = null)
    {
        if (null == $merchant_key) {
            $merchant_key = config('payment.pscb.secretKey');
        }

        $key_md5_binary = hash('md5', $merchant_key, true);

        return openssl_encrypt($message, 'AES-128-ECB', $key_md5_binary, OPENSSL_RAW_DATA);
    }
}
