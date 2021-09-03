# payment-manager

## Introduction

**Laravel Payment system manager.**

Менеджер подключения платежных систем для web приложений Laravel.

### Payment systems
* [ПСКБ](https://docs.pscb.ru/oos/api.html) ([doc](src/Pscb/README.md))

## Features
* php ^7.3|^8.0
* [Laravel v7.*](https://laravel.com/docs/7.x)

## Installation
### Composer

```shell script
composer require fitnesshouse/payment-manager
```

or add the following to your requirement part within the composer.json:

```json
{
    "require": {
        "fitnesshouse/payment-manager": "^2.*"
    }
}
```
and run command
```shell script
composer install
```

## Configure

Для базовой конфигурации в ```.env``` файле определите следующие обязательные параметры для платежной системы установленной по умолчанию (ПСКБ):

```dotenv
PSCB_MERCHANT_ID=123456789
PSCB_MERCHANT_KEY=111111
```

А так же переопределите необязательные параметры.
```dotenv
PSCB_REQUEST_URL=https://oos.pscb.ru/pay/
PSCB_MERCHANT_API_URL=https://oos.pscb.ru/merchantApi/
PSCB_SUCCESS_URL=https://youmarket.com/success
PSCB_FAIL_URL=https://youmarket.com/fail
PSCB_DISPLAY_LANGUAGE=RU
```

> См. [документацию к платежной системе](#payment-systems).

Если вам нужны расширенные свойства конфигурации, запустите:

```bash
$ php artisan vendor:publish --tag=payment-config
```

Эта команда создаст файл конфигурации ```\config\payment.php```

Платежная система по умолчанию установлена в файле конфигурации:

```php
// \config\payment.php
return [
    'system' => env('PAYMENT_SYSTEM', 'pscb'),
    
    /*
     * Настройки для ПСКБ
     * https://docs.pscb.ru/oos/index.html
     */
    'pscb' => [
        ...
    ]
]
```

## Integration
### Создание платежа
#### Запрос (Query)

В методе класса контроллера с помощью функции `Query::create(\Closure $callback)` создайте тело запроса для ссылки в платежную систему:
```php
$query = Query::create(function (QueryBuilder $builder) {
    $builder->orderId('TEST_123');
    $builder->amount(100.00);
    $builder->description('Тестовый платеж');
    $builder->customer([
        'phone' => '+7(123)-456-78-90',
        'email' => 'test@test.tt'
    ]);
    $builder->successUrl('https://youmarket.com/success');
    $builder->paymentMethod('ac');
});
```

Создать запрос для определенной платежной системы:
```php
$query = Query::paymentSystem('pscb')->create(function (QueryBuilder $builder) {
    $builder->orderId('TEST_123');
    $builder->amount(100.00);
    ...
});
```

Для каждой платежной системы реализуется свой класс интерфейса `QueryBuilder` со своими методами, необходимыми для создания запроса.

> См. [документацию к платежной системе](#payment-systems).

Получите сформированную ссылку: 
```php
$payUrl = $query->getPayUrl();
```

И перенаправьте клиента в платежную систему для оплаты:
```php
redirect($payUrl);
```

### Взаимодействие с платежной системой
#### Обработчик запросов (RequestHandler)

Для взаимодействия с платежной системой (request/response)
используйте класс `RequestHandler`

Создать запрос:
```php
$requestHandler = RequestHandler::create('checkPayment', ['orderId' => 'TEST_123']);
```

Отправить запрос и получить ответ:
```php
$response = $requestHandler->send();
```

Создать запрос для определенной платежной системы:
```php
$requestHandler = RequestHandler::paymentSystem('pscb')
                        ->create('checkPayment', ['orderId' => 'TEST_123']);
```
