<?php

namespace Fh\PaymentManager;

use Fh\PaymentManager\Contracts\PaymentSystem;
use Fh\PaymentManager\Factories\PaymentFactory;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class PaymentManager
{
    private $app;
    /**
     * @var PaymentFactory
     */
    private $factory;

    /**
     * @var array
     */
    private $paymentSystems = [];

    /**
     * @param Application $app
     * @param PaymentFactory $factory
     */
    public function __construct(Application $app, PaymentFactory $factory)
    {
        $this->app = $app;
        $this->factory = $factory;
    }

    /**
     * @throws BindingResolutionException
     */
    public function paymentSystem($name = null)
    {
        $name = $name ?: $this->parseSystemName($name);

        if (!isset($this->paymentSystems[$name])) {
            $this->paymentSystems[$name] = $this->makePaymentSystem($name);
        }

        return $this->paymentSystems[$name];
    }

    /**
     * @param $name
     * @return string
     */
    private function parseSystemName($name): string
    {
        $name = $name ?: $this->getDefaultPaymentSystem();

        return Str::lower($name);
    }

    /**
     * @return string
     */
    public function getDefaultPaymentSystem(): string
    {
        return $this->app['config']['payment.system'];
    }

    /**
     * @throws BindingResolutionException
     */
    private function makePaymentSystem(string $name): PaymentSystem
    {
        $config = $this->configuration($name);

        return $this->factory->make($config, $name);
    }

    /**
     * @param string $name
     * @return mixed
     */
    private function configuration(string $name)
    {
        $name = $name ?: $this->getDefaultPaymentSystem();

        $paymentSystems = Arr::except($this->app['config']['payment'], ['system']);

        if (is_null($config = Arr::get($paymentSystems, $name))) {
            throw new \InvalidArgumentException("Unsupported payment system [{$name}].");
        }

        return $config;
    }
}