<?php

return [
    'system' => env('PAYMENT_SYSTEM', 'pscb'),

    /*
     * Настройки для ПСКБ
     * https://docs.pscb.ru/oos/index.html
     */
    'pscb' => [

        /*
         * ID магазина
         * использовать значение из Кабинета мерчанта
         */
        'marketPlace' => env('PSCB_MERCHANT_ID', ''),

        /*
         * Ключ API
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
         *
         * String, maxlength: 1024
         */
        'successUrl' => env('PSCB_SUCCESS_URL', ''),

        /*
         * Адрес возврата в случае неуспешной оплаты
         * покупатель будет перенаправлен по нему после неуспешной оплаты
         * если адреса отсутствуют в запросе, они берутся из соответствующих настроек Кабинета мерчанта
         * если адреса не указаны и в Кабинете, плательщик останется на странице успеха/неуспеха на стороне Банка
         *
         * String; maxlength: 1024
         */
        'failUrl' => env('PSCB_FAIL_URL', ''),
    ],
];
