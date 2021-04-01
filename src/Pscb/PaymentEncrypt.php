<?php

declare(strict_types=1);

namespace Fh\PaymentManager\Pscb;

class PaymentEncrypt
{
    /**
     * Функция расшифровки сообщения от ПСКБ.
     * @param $encrypted mixed зашифрованное сообщение; массив байтов
     * @param null $key string секретный ключ мерчанта; текстовая строка
     * @return mixed расшифрованное сообщение; массив байтов, одновременно - строка в кодировке UTF-8
     */
    final public static function decrypt($encrypted, $key = null)
    {
        if (null == $key) {
            $key = config('payment.pscb.secretKey');
        }

        $key_md5_binary = hash('md5', $key, true);

        return \openssl_decrypt($encrypted, 'AES-128-ECB', $key_md5_binary, OPENSSL_RAW_DATA);
    }

    /**
     * Функция шифровки сообщения ПСКБ.
     * @param $message string текстовое сообщение - строка в кодировке UTF-8
     * @param null $key string секретный ключ мерчанта; текстовая строка
     * @return mixed зашифрованное сообщение; массив байтов
     */
    final public static function encrypt(string $message, $key = null)
    {
        if (null == $key) {
            $key = config('payment.pscb.secretKey');
        }

        $key_md5_binary = hash('md5', $key, true);

        return \openssl_encrypt($message, 'AES-128-ECB', $key_md5_binary, OPENSSL_RAW_DATA);
    }
}
