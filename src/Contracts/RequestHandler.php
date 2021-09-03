<?php

namespace Fh\PaymentManager\Contracts;

interface RequestHandler
{
    /**
     * @return mixed
     */
    public function send();

    /**
     * @return mixed
     */
    public function createRequest(string $url, array $params = []);
}
