<?php

namespace Fh\PaymentManager\Pscb;

use Fh\PaymentManager\Payments\QueryBuilder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class PscbQueryBuilder implements QueryBuilder
{
    /**
     * Сумма платежа в рублях. Разделитель целой и дробной части – точка.
     * min: 1.00.
     *
     * @var float
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

    public function __construct()
    {
        $this->initConfigParams();
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
     * Случайная строка для соблюдения уникальности каждого запроса к API.
     *
     * @return PscbQueryBuilder
     */
    public function setNonce(): PscbQueryBuilder
    {
        $this->nonce = sha1(time() . Str::random(8));

        return $this;
    }

    /**
     * Идентификатор платежа, отображаемый Плательщику.
     *
     * @param string $showOrderId
     * @return PscbQueryBuilder
     */
    public function showOrderId(string $showOrderId): PscbQueryBuilder
    {
        $this->showOrderId = $showOrderId;

        return $this;
    }

    /**
     * Детали платежа. Используется для передачи любых дополнительных параметров.
     * Краткое общее описание (например "Пополнение счета").
     *
     * @param string $description
     * @return PscbQueryBuilder
     */
    public function description(string $description): PscbQueryBuilder
    {
        $this->details = $description;

        return $this;
    }

    /**
     * Платежный метод, с использованием которого будет совершен платёж.
     *
     * @param string $paymentMethod
     * @return PscbQueryBuilder
     */
    public function paymentMethod(string $paymentMethod): PscbQueryBuilder
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    /**
     * URL для перенаправления Плательщика при успешной оплате.
     *
     * @param string $successUrl
     * @return PscbQueryBuilder
     */
    public function successUrl(string $successUrl): PscbQueryBuilder
    {
        $this->successUrl = $successUrl;

        return $this;
    }

    /**
     * URL для перенаправления Плательщика в случае неуспешной оплаты.
     *
     * @param string $failUrl
     * @return PscbQueryBuilder
     */
    public function failUrl(string $failUrl): PscbQueryBuilder
    {
        $this->failUrl = $failUrl;

        return $this;
    }

    /**
     * Язык платёжных страниц
     *
     * @param string $displayLanguage
     * @return PscbQueryBuilder
     */
    public function displayLanguage(string $displayLanguage): PscbQueryBuilder
    {
        $this->displayLanguage = $displayLanguage;

        return $this;
    }

    /**
     * Массив дополнительных параметров.
     *
     * @param array $data
     * @return PscbQueryBuilder
     */
    public function data(array $data): PscbQueryBuilder
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param array $attributes
     * @return PscbQueryBuilder
     */
    public function customer(array $attributes): PscbQueryBuilder
    {
        $this->customerAccount = Arr::get($attributes, 'account', '');
        $this->customerEmail = Arr::get($attributes, 'email', '');
        $this->customerPhone = Arr::get($attributes, 'phone', '');

        return $this;
    }

    /**
     * @param string $customerAccount
     * @return PscbQueryBuilder
     */
    public function customerAccount(string $customerAccount): PscbQueryBuilder
    {
        $this->customerAccount = $customerAccount;

        return $this;
    }

    /**
     * @param string $customerComment
     * @return PscbQueryBuilder
     */
    public function customerComment(string $customerComment): PscbQueryBuilder
    {
        $this->customerComment = $customerComment;

        return $this;
    }

    /**
     * @param string $customerEmail
     * @return PscbQueryBuilder
     */
    public function customerEmail(string $customerEmail): PscbQueryBuilder
    {
        $this->customerEmail = $customerEmail;

        return $this;
    }

    /**
     * @param string $customerPhone
     * @return PscbQueryBuilder
     */
    public function customerPhone(string $customerPhone): PscbQueryBuilder
    {
        $this->customerPhone = $customerPhone;

        return $this;
    }

    /**
     * Сумма платежа в рублях. Разделитель целой и дробной части – точка.
     *
     * @param float $amount
     * @return PscbQueryBuilder
     */
    public function amount($amount): PscbQueryBuilder
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Уникальный идентификатор платежа на стороне Мерчанта.
     *
     * @param string $orderId
     * @return PscbQueryBuilder
     */
    public function orderId(string $orderId): PscbQueryBuilder
    {
        $this->orderId = $orderId;

        return $this;
    }

    public function getPayUrl(string $marketPlace = ''): string
    {
        if (!isset($this->amount, $this->orderId)) {
            throw new \InvalidArgumentException("Необходимо установить 'amount' и 'orderId'");
        }

        $request_url = config('payment.pscb.requestUrl');
        $message = $this->toJson();

        $params = [
            'marketPlace' => config('payment.pscb.marketPlace'),
            'message' => base64_encode($message),
            'signature' => PaymentRequest::signature($message),
        ];

        return url($request_url) . '?' . http_build_query($params);
    }

    /**
     * @param int $options
     * @return string
     */
    public function toJson($options = 0): string
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
     * @param array $arguments
     * @return PscbQueryBuilder
     */
    public function setParams(array $arguments): PscbQueryBuilder
    {
        if (!empty($arguments)) {
            foreach ($arguments as $param => $argument) {
                property_exists($this, $param) && $this->{$param} = $argument;
            }
        }

        return $this;
    }
}