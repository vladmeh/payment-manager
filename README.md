[![Build Status](https://travis-ci.com/vladmeh/payment-manager.svg?branch=master)](https://travis-ci.com/vladmeh/payment-manager)
[![StyleCI](https://github.styleci.io/repos/334944839/shield?branch=master)](https://github.styleci.io/repos/334944839?branch=master)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/654b16db2d794a3fabe5f5f832ca7283)](https://www.codacy.com/gh/vladmeh/payment-manager/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=vladmeh/payment-manager&amp;utm_campaign=Badge_Grade)
[![Latest Stable Version](https://poser.pugx.org/vladmeh/payment-manager/v)](//packagist.org/packages/vladmeh/payment-manager) 
[![Daily Downloads](https://poser.pugx.org/vladmeh/payment-manager/d/daily)](//packagist.org/packages/vladmeh/payment-manager)
[![License](https://poser.pugx.org/vladmeh/payment-manager/license)](//packagist.org/packages/vladmeh/payment-manager)

# payment-manager

## Introduction

**Laravel Payment system manager.**

Упрощеная (начальная) версия взаимодействия web интерфейса с платежными системами для фреймворка Laravel

_Реализовано:_
* Платежная система - [ПСКБ](https://docs.pscb.ru/oos/api.html)
* Базовый интерфейс для создания платежа
* Запрос параметров платежа
* Запрос списка платежей

_Планируется:_
* Подключение нескольких платежных систем
* Создание, подтверждение и отмена двухстадийных платежей (холдирование)
* Создание и отмена рекуррентных платежей (автоплатёж)
* REST API для администратора 
* Базовая административная панель

## Installation

### Composer

```shell script
composer require vladmeh/payment-manager
```

or add the following to your requirement part within the composer.json:

```json
{
    "require": {
        "vladmeh/payment-manager": "^2.*"
    }
}
```
## Configure

Для базовой конфигурации в ```.env``` файле нужно определить следующие параметры:

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
# покупатель будет перенаправлен по нему после неуспешной оплаты
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
```

Если вам нужны расширенные свойства конфигурации, запустите:

```bash
$ php artisan vendor:publish --tag=payment-config
```

Эта команда создаст файл конфигурации ```\config\payment.php```

## Integration

См. [PaymentServiceTest.php](tests/Pscb/PaymentServiceTest.php)