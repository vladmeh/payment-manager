<?php


namespace Fh\PaymentManager\Contracts;


interface Buyable
{
    /**
     * @param array $options
     * @return int|string
     */
    public function getBuyableIdentifier(array $options = []);

    /**
     * @param array $options
     * @return string
     */
    public function getBuyableDescription(array $options = []): string;

    /**
     * @param array $options
     * @return int
     */
    public function getBuyablePrice(array $options = []): int;
}