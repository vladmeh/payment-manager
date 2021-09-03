<?php

namespace Fh\PaymentManager\Requests;

use Fh\PaymentManager\Contracts\RequestHandler;

class PaymentRequestHandler
{
    /**
     * @var RequestHandler
     */
    private $requestHandler;

    public function __construct(RequestHandler $requestHandler)
    {
        $this->requestHandler = $requestHandler;
    }

    /**
     * @param string $url
     * @param array $params
     * @return PaymentRequestHandler
     */
    public function create(string $url, array $params = []): PaymentRequestHandler
    {
        return $this->build(tap($this->requestHandler(), function (RequestHandler $handler) use ($url, $params) {
            $handler->createRequest($url, $params);
        }));
    }

    /**
     * @param RequestHandler $handler
     * @return PaymentRequestHandler
     */
    private function build(RequestHandler $handler): PaymentRequestHandler
    {
        $this->requestHandler = $handler;

        return $this;
    }

    /**
     * @return RequestHandler
     */
    public function requestHandler(): RequestHandler
    {
        return $this->requestHandler;
    }

    /**
     * @return mixed
     */
    public function send()
    {
        return $this->requestHandler()->send();
    }
}
