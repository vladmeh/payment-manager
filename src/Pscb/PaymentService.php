<?php


namespace Vladmeh\PaymentManager\Pscb;



use Vladmeh\PaymentManager\Contracts\PaymentCustomer;
use Vladmeh\PaymentManager\Contracts\PaymentOrder;

class PaymentService
{
    /**
     * Создание данных для запроса создания платежа в ПСКБ
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
     * @param string|null $marketPlace Идентификатор Магазина, которому принадлежит orderId.
     * @param bool $requestCardData Флаг запроса расширенной информации о платеже банковской картой.
     * @param bool $requestFiscalData Флаг запроса информации о чеках, связанных с платежом.
     *
     * @return bool|string
     */
    public function checkPayment(string $orderId, string $marketPlace = null, bool $requestCardData = false, bool $requestFiscalData = false)
    {
        $marketPlace = $marketPlace ?? config('payment.pscb.marketPlace');

        $messageText = json_encode(compact('orderId', 'marketPlace', 'requestCardData', 'requestFiscalData'));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, config('payment.pscb.merchantApiUrl') . 'checkPayment');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $messageText);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('signature: ' . $this->signature($messageText)));
        $out = curl_exec($ch);
        curl_close($ch);

        return $out;
    }

    /**
     * Подпись сообщения с использованием ключа API secretKey.
     *
     * @param string $messageText JSON UTF8
     * @return string
     */
    public final function signature(string $messageText): string
    {
        return hash('sha256', $messageText . config('payment.pscb.secretKey'));
    }

    /**
     * Формирование URL запроса создания платежа
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
            "message" => base64_encode($message),
            "signature" => $this->signature($message)
        ];

        return url($request_url) . '?' . http_build_query($params);
    }

    /**
     * Функция расшифровки сообщения от ПСКБ.
     * @param $encrypted mixed зашифрованное сообщение; массив байтов
     * @param null $merchant_key string секретный ключ мерчанта; текстовая строка
     * @return mixed расшифрованное сообщение; массив байтов, одновременно - строка в кодировке UTF-8
     */
    public function decrypt($encrypted, $merchant_key = null)
    {
        if (null == $merchant_key) {
            $merchant_key = config('payment.pscb.secretKey');
        }

        $key_md5_binary = hash("md5", $merchant_key, true);
        return openssl_decrypt($encrypted, "AES-128-ECB", $key_md5_binary, OPENSSL_RAW_DATA);
    }

    /**
     * Функция шифровки сообщения ПСКБ.
     * @param $message string текстовое сообщение - строка в кодировке UTF-8
     * @param null $merchant_key string секретный ключ мерчанта; текстовая строка
     * @return mixed зашифрованное сообщение; массив байтов
     */
    public function encrypt(string $message, $merchant_key = null)
    {
        if (null == $merchant_key) {
            $merchant_key = config('payment.pscb.secretKey');
        }

        $key_md5_binary = hash("md5", $merchant_key, true);
        return openssl_encrypt($message, "AES-128-ECB", $key_md5_binary, OPENSSL_RAW_DATA);
    }
}