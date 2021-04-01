<?php

namespace Fh\PaymentManager\Tests;

class ConfigTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_get_config_payment(): void
    {
        $config = config('payment');
        $this->assertIsArray($config);
        $this->assertArrayHasKey('pscb', $config);

        $this->assertIsArray(config('payment.pscb'));
    }

    /**
     * @test
     */
    public function it_can_be_get_config_logging_payment(): void
    {
        $config = config('logging.channels');
        $this->assertArrayHasKey('payment', $config);
    }
}
