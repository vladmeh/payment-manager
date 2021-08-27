<?php

namespace Fh\PaymentManager\Payments;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Arr;

class PaymentFactory
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }


    /**
     * @param array $config
     * @param null $name
     * @return PaymentSystem
     * @throws BindingResolutionException
     */
    public function make(array $config, $name = null): PaymentSystem
    {
        $config = $this->parseConfig($config, $name);

        return $this->createPaymentSystem($config);
    }

    private function parseConfig(array $config, $name): array
    {
        return Arr::add($config, 'name', $name);
    }


    /**
     * @param array $config
     * @return PaymentSystem
     * @throws BindingResolutionException
     */
    private function createPaymentSystem(array $config): PaymentSystem
    {
        if (!isset($config['name'])) {
            throw new \InvalidArgumentException('A payment system must be specified [name].');
        }

        if ($this->container->bound($key = "payment.system.{$config['name']}")) {
            return $this->container->make($key);
        }

        throw new \InvalidArgumentException("Unsupported payment system [{$config['name']}]");
    }
}
