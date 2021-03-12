<?php

declare(strict_types=1);

namespace Vladmeh\PaymentManager\Pscb;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Str;

/**
 * Class PaymentRequestData
 * Объект с данными для запроса создания платежа в системе ПСКБ.
 * @link https://docs.pscb.ru/oos/api.html#api-magazina-sozdanie-platezha-zapros
 */
class MessageRequestBuilder implements Arrayable, Jsonable
{
    /**
     * Сумма платежа в рублях. Разделитель целой и дробной части – точка.
     * min: 1.00.
     *
     * @var int
     */
    private $amount;

    /**
     * Уникальный идентификатор платежа на стороне Мерчанта.
     * minlength: 4, maxlength: 20.
     *
     * @var string
     */
    private $orderId;

    /**
     * Идентификатор платежа, отображаемый Плательщику.
     * Если не передан или пустой, значение заимствуется из orderId.
     * minlength: 4, maxlength: 20.
     *
     * @var string
     */
    private $showOrderId = '';

    /**
     * Детали платежа. Используется для передачи любых дополнительных параметров.
     * maxlength: 2048.
     *
     * @var string
     */
    private $details = '';

    /**
     * Платежный метод, с использованием которого будет совершен платёж.
     * допустимые значения параметра:
     *  ''              - выбор метода оплаты предоставляется пользователю
     *  'ac'            - банковские карты
     *  'ym'            - Яндекс-деньги
     *  'qiwi'          - QIWI-кошелек
     *  'wm'            - WebMoney (WMR)
     *  'alfa'          - Альфа-клик
     *  'pscb_terminal' - терминал ПСКБ
     *  'mobi-money'    - Мобильный платеж
     * Если параметр не передан, Система предложит Плательщику выбрать Платежный метод самостоятельно.
     *
     * @var string
     */
    private $paymentMethod = '';

    /**
     * Уникальный идентификатор Плательщика.
     * Если включена опция сохранения данных карты, используется для создания связки
     * “плательщик - карта” в рамках конкретного Магазина.
     * minlength: 4, maxlength: 20.
     *
     * @var string
     */
    private $customerAccount = '';

    /**
     * Комментарий Плательщика.
     * maxlength: 255.
     *
     * @var string
     */
    private $customerComment = '';

    /**
     * Контактный e-mail Плательщика.
     * maxlength: 512.
     *
     * @var string
     */
    private $customerEmail = '';

    /**
     * Контактный телефон Плательщика в международном формате.
     * maxlength: 32.
     *
     * @var string
     */
    private $customerPhone = '';

    /**
     * URL для перенаправления Плательщика при успешной оплате.
     * Если не передан, Плательщик будет перенаправлен на URL,
     * указанный в настройках Магазина в Кабинете мерчанта.
     *
     * maxlength: 1024
     *
     * @var string
     */
    private $successUrl = '';

    /**
     * URL для перенаправления Плательщика в случае неуспешной оплаты.
     * Если не передан, Плательщик будет перенаправлен на URL,
     * указанный в настройках Магазина в Кабинете мерчанта.
     *
     * если адреса не указаны и в Кабинете, плательщик останется на странице успеха/неуспеха на стороне Банка
     *
     * maxlength: 1024
     *
     * @var string
     */
    private $failUrl = '';

    /**
     * Язык платёжных страниц
     * допустимые значения параметра:
     * '' - язык выберется автоматически
     * 'RU' - русский
     * 'EN' - английский.
     *
     * @var string
     */
    private $displayLanguage = '';

    /**
     * Случайная строка для соблюдения уникальности каждого запроса к API (противодействует атаке полного повтора запроса).
     *
     * @var string
     */
    private $nonce = '';

    /**
     * Массив дополнительных параметров.
     * - hold - Boolean Определяет схему авторизации при проведении платежа (см. Холдирование). Возможные значения: true, false (значение по умолчанию).
     * - fdReceipt    - Массив параметров фискального чека (см. Описание параметров чека).
     * - template - String    Уникальный идентификатор кастомизированного шаблона платежной страницы (см. Дизайн страницы оплаты).
     * - user    String    Логин в Visa QIWI Wallet (номер телефона в международном формате). Используется для выставления счета в Visa QIWI Wallet (Платежный метод “QIWI Кошелек”).
     * - userPhone    String    Номер телефона в международном формате. Используется для выставления счета на мобильный телефон (Платежный метод “Мобильный платеж”).
     * - userAccount    String    Логин в интернет-банке “Альфа-Клик”. Используется для выставления счета в “Альфа-Клик” (Платежный метод “Альфа-Клик”).
     * - debug    Boolean    Включает отображение отладочной информации в браузере Плательщика в случае ошибок. Используется в целях разработки. Возможные значения: true, false (значение по умолчанию).
     *
     * @link https://docs.pscb.ru/oos/api.html#api-magazina-sozdanie-platezha-zapros
     *
     * @var array
     */
    private $data = [];

    /**
     * PaymentMessage constructor.
     * @param int $amount
     * @param string $orderId
     * @param array ...$params
     */
    public function __construct(int $amount, string $orderId, array $params = [])
    {
        $this->amount = $amount;
        $this->orderId = $orderId;

        $this->initConfigParams();
        $this->setParams($params);
    }

    /**
     * @return void
     */
    private function initConfigParams(): void
    {
        config('payment.pscb.paymentMethod') && $this->paymentMethod = config('payment.pscb.paymentMethod');
        config('payment.pscb.successUrl') && $this->successUrl = config('payment.pscb.successUrl');
        config('payment.pscb.failUrl') && $this->failUrl = config('payment.pscb.failUrl');
        config('payment.pscb.displayLanguage') && $this->displayLanguage = config('payment.pscb.displayLanguage');
    }

    /**
     * @param array $arguments
     * @return void
     */
    public function setParams(array $arguments)
    {
        if (!empty($arguments)) {
            foreach ($arguments as $param => $argument) {
                property_exists($this, $param) && $this->{$param} = $argument;
            }
        }
    }

    /**
     * @param int $amount
     * @param string $orderId
     * @param array $params
     * @return MessageRequestBuilder
     */
    public static function make(int $amount, string $orderId, array $params = []): MessageRequestBuilder
    {
        return new static($amount, $orderId, $params);
    }

    /**
     * @param int $options
     * @return false|string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array_filter(get_object_vars($this));
    }

    /**
     * Случайная строка для соблюдения уникальности каждого запроса к API.
     *
     * @return MessageRequestBuilder
     */
    public function setNonce(): self
    {
        $this->nonce = sha1(time() . Str::random(8));

        return $this;
    }

    /**
     * @param string $showOrderId
     * @return MessageRequestBuilder
     */
    public function setShowOrderId(string $showOrderId): self
    {
        $this->showOrderId = $showOrderId;

        return $this;
    }

    /**
     * @param string $details
     * @return MessageRequestBuilder
     */
    public function setDetails(string $details): self
    {
        $this->details = $details;

        return $this;
    }

    /**
     * @param string $paymentMethod
     * @return MessageRequestBuilder
     */
    public function setPaymentMethod(string $paymentMethod): self
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    /**
     * @param string $successUrl
     * @return MessageRequestBuilder
     */
    public function setSuccessUrl(string $successUrl): self
    {
        $this->successUrl = $successUrl;

        return $this;
    }

    /**
     * @param string $failUrl
     * @return MessageRequestBuilder
     */
    public function setFailUrl(string $failUrl): self
    {
        $this->failUrl = $failUrl;

        return $this;
    }

    /**
     * @param string $displayLanguage
     * @return MessageRequestBuilder
     */
    public function setDisplayLanguage(string $displayLanguage): self
    {
        $this->displayLanguage = $displayLanguage;

        return $this;
    }

    /**
     * @param array $data
     * @return MessageRequestBuilder
     */
    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param string $customerAccount
     * @return MessageRequestBuilder
     */
    public function setCustomerAccount(string $customerAccount): self
    {
        $this->customerAccount = $customerAccount;

        return $this;
    }

    /**
     * @param string $customerComment
     * @return MessageRequestBuilder
     */
    public function setCustomerComment(string $customerComment): self
    {
        $this->customerComment = $customerComment;

        return $this;
    }

    /**
     * @param string $customerEmail
     * @return MessageRequestBuilder
     */
    public function setCustomerEmail(string $customerEmail): self
    {
        $this->customerEmail = $customerEmail;

        return $this;
    }

    /**
     * @param string $customerPhone
     * @return MessageRequestBuilder
     */
    public function setCustomerPhone(string $customerPhone): self
    {
        $this->customerPhone = $customerPhone;

        return $this;
    }
}
