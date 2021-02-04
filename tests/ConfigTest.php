<?php

namespace Vladmeh\PaymentManager\Tests;

class ConfigTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_get_config(): void
    {
        $config = config('payment');
        $this->assertIsArray($config);
        $this->assertArrayHasKey('pscb', $config);

        $this->assertIsArray(config('payment.pscb'));
    }
}
