<?php

namespace Fh\PaymentManager\Contracts;

interface RequestHandler
{
    /**
     * @return mixed
     */
    public function send(array $options = []);

    /**
     * @return mixed
     */
    public function createRequest(string $url, array $params = []);
}
