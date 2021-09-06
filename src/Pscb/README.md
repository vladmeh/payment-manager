## ПСКБ
[https://docs.pscb.ru/oos/index.html]()

### Configure
#### config\payment.php

```php
return [
    'system' => env('PAYMENT_SYSTEM', 'pscb'),
    
    /*
     * Настройки для ПСКБ
     * https://docs.pscb.ru/oos/index.html
     */
    'pscb' => [
        /*
         * ID магазина (обязательно)
         * использовать значение из Кабинета мерчанта
         */
        'marketPlace' => env('PSCB_MERCHANT_ID', ''),

        /*
         * Ключ API (обязательно)
         * использовать значение из Кабинета мерчанта
         */
        'secretKey' => env('PSCB_MERCHANT_KEY', '111111'),

        /*
         * Адрес запроса плетежей
         * для тестирования используйте https://oosdemo.pscb.ru/pay/
         */
        'requestUrl' => env('PSCB_REQUEST_URL', 'https://oosdemo.pscb.ru/pay/'),

        /*
         * Адрес запроса для дополнительных возможностей
         * Адрес запроса зависит от вызываемого метода API.
         * для тестирования используйте https://oos.pscb.ru/merchantApi/
         */
        'merchantApiUrl' => env('PSCB_MERCHANT_API_URL', 'https://oosdemo.pscb.ru/merchantApi/'),

        /*
         * Адрес возврата в случае успешной оплаты
         * покупатель будет перенаправлен по нему после успешной оплаты
         * если адреса отсутствуют в запросе, они берутся из соответствующих настроек Кабинета мерчанта
         * если адреса не указаны и в Кабинете, плательщик останется на странице успеха/неуспеха на стороне Банка
         */
        'successUrl' => env('PSCB_SUCCESS_URL', ''),

        /*
         * Адрес возврата в случае неуспешной оплаты
         * покупатель будет перенаправлен по нему после неуспешной оплаты
         * если адреса отсутствуют в запросе, они берутся из соответствующих настроек Кабинета мерчанта
         * если адреса не указаны и в Кабинете, плательщик останется на странице успеха/неуспеха на стороне Банка
         */
        'failUrl' => env('PSCB_FAIL_URL', ''),

        /*
         * Язык платёжных страниц
         * допустимые значения параметра:
         * '' - язык выберется автоматически
         * 'RU' - русский (по умолчанию)
         * 'EN' - английский.
         */
        'displayLanguage' => env('PSCB_DISPLAY_LANGUAGE', 'RU')
    ]
    
    ...
]
```

#### .env
Обязательные параметры:
```dotenv
# ID магазина, использовать значение из Кабинета мерчанта
PSCB_MERCHANT_ID=123456789

#Ключ API, спользовать значение из Кабинета мерчанта
PSCB_MERCHANT_KEY=111111
```

Необязательные параметры:

```dotenv
# Адрес запроса плетежей
# по умолчанию https://oosdemo.pscb.ru/pay/
PSCB_REQUEST_URL=https://oos.pscb.ru/pay/

# Адрес запроса для дополнительных возможностей
# Адрес запроса зависит от вызываемого метода API.
# по умолчанию https://oosdemo.pscb.ru/merchantApi/
PSCB_MERCHANT_API_URL=https://oos.pscb.ru/merchantApi/

# Адрес возврата в случае успешной оплаты
# покупатель будет перенаправлен по нему после успешной оплаты
# если адреса отсутствуют в запросе, они берутся из соответствующих настроек Кабинета мерчанта
# если адреса не указаны и в Кабинете, плательщик останется на странице успеха/неуспеха на
# стороне Банка       
PSCB_SUCCESS_URL=https://youmarket.com/success

# Адрес возврата в случае неуспешной оплаты
# покупатель будет перенаправлен по нему после неуспешной оплаты
# если адреса отсутствуют в запросе, они берутся из соответствующих настроек Кабинета мерчанта
# если адреса не указаны и в Кабинете, плательщик останется на странице успеха/неуспеха на
# стороне Банка       
PSCB_FAIL_URL=https://youmarket.com/fail

# Язык платёжных страниц
# допустимые значения параметра:
# '' - язык выберется автоматически
# 'RU' - русский (по умолчанию)
# 'EN' - английский.
PSCB_DISPLAY_LANGUAGE=RU
```

### Запрос (QueryBuilder)


Если ПСКБ установлена как платежная система по умолчанию:
```php
use \Fh\PaymentManager\Facades\Payment;

$query = Payment::query(function (QueryBuilder $builder) {
    $builder->orderId('TEST_123');
    $builder->amount(100.00);
    ...
});

$query->getPayUrl();
```

Иначе:
```php
use \Fh\PaymentManager\Facades\Payment;

$query = Payment::system('pscb')->createQuery(function (QueryBuilder $builder) {
    $builder->orderId('TEST_123');
    $builder->amount(100.00);
    ...
});

$query->getPayUrl();
```

Доступные методы для создания запроса:
См. [https://docs.pscb.ru/oos/api.html#api-magazina-sozdanie-platezha-zapros]()
```php
// Обязательный. Сумма платежа в рублях. Разделитель целой и дробной части – точка.
public function amount($amount);

// Обязательный. Уникальный идентификатор платежа на стороне Мерчанта.
public function orderId(string $orderId);

// Необязательные
// Идентификатор платежа, отображаемый Плательщику.
public function showOrderId(string $showOrderId);

// Детали платежа. Используется для передачи любых дополнительных параметров.
// Краткое общее описание (например "Пополнение счета").
public function description(string $description);

// Платежный метод, с использованием которого будет совершен платёж.
public function paymentMethod(string $paymentMethod);

// URL для перенаправления Плательщика при успешной оплате.
public function successUrl(string $successUrl);

// URL для перенаправления Плательщика в случае неуспешной оплаты.
public function failUrl(string $failUrl);

// Язык платёжных страниц
public function displayLanguage(string $displayLanguage);

// Массив параметров для плательщика.
// $attributes = [
//     'account' => '1234567890',
//     'phone' => '+7(123)456-78-90',
//     'email' => 'test@test.tt'
//  ]
public function customer(string[] $attributes);

// Уникальный идентификатор Плательщика.
public function customerAccount(string $customerAccount);

// Комментарий Плательщика.
public function customerComment(string $customerComment);

// Контактный e-mail Плательщика.
public function customerEmail(string $customerEmail);

// Контактный телефон Плательщика
public function customerPhone(string $customerPhone);

// Массив дополнительных параметров.
public function data(array $data);

// Случайная строка для соблюдения уникальности каждого запроса к API.
public function setNonce();
```

Создание запроса с параметрами указанными в конфигурации платежной системы:
```php
use \Fh\PaymentManager\Facades\Payment;

$query = Payment::query()->create();

// или
$query = Payment::system('pscb')->createQuery();
```

Получить сформированную ссылку и перенаправить клиента в платежную систему для оплаты:
```php
$payUrl = $query->getPayUrl();
redirect($payUrl);
```

### Обработчик запросов (RequestHandler)

См. [https://docs.pscb.ru/oos/api.html#api-dopolnitelnyh-vozmozhnostej-shema-vzaimodejstviya-zapros](https://docs.pscb.ru/oos/api.html#api-dopolnitelnyh-vozmozhnostej-shema-vzaimodejstviya-zapros)

Создать запрос:
```php
use \Fh\PaymentManager\Facades\Payment;

// Произвольный запрос если ПСКБ установлена по умолчанию
$requestHandler = Payment::requestHandler()->create('checkPayment', ['orderId' => 'TEST_123']);

// Произвольный запрос если ПСКБ не установлена по умолчанию
$requestHandler = Payment::system('pscb')->requestHandler()->create('checkPayment', ['orderId' => 'TEST_123']);
```

[Запрос параметров платежа:](https://docs.pscb.ru/oos/api.html#api-dopolnitelnyh-vozmozhnostej-zapros-parametrov-platezha)
```php
use \Fh\PaymentManager\Facades\Payment;

// Если ПСКБ установлена по умолчанию
$requestHandler = Payment::requestHandler()->checkPayment();

// Если ПСКБ не установлена по умолчанию
$requestHandler = Payment::system('pscb')->requestHandler()->checkPayment();
```

[Запрос списка платежей:](https://docs.pscb.ru/oos/api.html#api-dopolnitelnyh-vozmozhnostej-zapros-spiska-platezhej)
```php
use \Fh\PaymentManager\Facades\Payment;

// Если ПСКБ установлена по умолчанию
$requestHandler = Payment::requestHandler()->getPayments();

// Если ПСКБ не установлена по умолчанию
$requestHandler = Payment::system('pscb')->requestHandler()->getPayments();
```

Отправить запрос и получить ответ:
```php
$response = $requestHandler->send();
```