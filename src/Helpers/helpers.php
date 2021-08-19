<?php

if(!\function_exists('phone_digits')){

    /**
     * @param string $phone
     * @param int $offset
     * @return false|string
     */
    function phone_digits(string $phone, int $offset = -10) {
        $digits = \preg_replace('/[^0-9]/', '', $phone);

        return substr($digits, $offset);
    }
}
