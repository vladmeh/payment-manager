<?php

namespace Fh\PaymentManager\Tests\Pscb;

use Fh\PaymentManager\Pscb\PscbQueryBuilder;
use Fh\PaymentManager\Tests\TestCase;

class PscbQueryBuilderTest extends TestCase
{
    const AMOUNT = 100.00;
    const ORDER_ID = '123';
    const SHOW_ORDER_ID = '456';
    const PAYMENT_METHOD = 'ac';
    const DETAILS = 'test details';
    const SUCCESS_URL = 'http://test.example.com/success';
    const FAIL_URL = 'http://test.example.com/fail';
    const CUSTOMER_ACCOUNT = '1234567890';
    const CUSTOMER_EMAIL = 'customer@email.test';
    const CUSTOMER_PHONE = '+7(123)456-78-90';
    const CUSTOMER_COMMENT = 'comment';
    const DISPLAY_LANGUAGE = 'RU';
    const RECURRENTABLE = true;
    const DATA = ['debug' => true];


    /**
     * @var PscbQueryBuilder
     */
    private $builder;

    /**
     * @test
     */
    public function it_can_be_build_query(): void
    {
        $this->builder
            ->amount(self::AMOUNT)
            ->orderId(self::ORDER_ID)
            ->showOrderId(self::SHOW_ORDER_ID)
            ->paymentMethod(self::PAYMENT_METHOD)
            ->description(self::DETAILS)
            ->successUrl(self::SUCCESS_URL)
            ->failUrl(self::FAIL_URL)
            ->customerAccount(self::CUSTOMER_ACCOUNT)
            ->customerEmail(self::CUSTOMER_EMAIL)
            ->customerPhone(self::CUSTOMER_PHONE)
            ->customerComment(self::CUSTOMER_COMMENT)
            ->displayLanguage(self::DISPLAY_LANGUAGE)
            ->recurrentable(self::RECURRENTABLE)
            ->data(self::DATA)
            ->setNonce();

        $message = $this->builder->toArray();

        $this->assertIsArray($message);
        $this->assertArrayHasKey('amount', $message);
        $this->assertArrayHasKey('orderId', $message);
        $this->assertArrayHasKey('showOrderId', $message);
        $this->assertArrayHasKey('details', $message);
        $this->assertArrayHasKey('paymentMethod', $message);
        $this->assertArrayHasKey('customerAccount', $message);
        $this->assertArrayHasKey('customerComment', $message);
        $this->assertArrayHasKey('customerEmail', $message);
        $this->assertArrayHasKey('customerPhone', $message);
        $this->assertArrayHasKey('successUrl', $message);
        $this->assertArrayHasKey('failUrl', $message);
        $this->assertArrayHasKey('nonce', $message);
        $this->assertArrayHasKey('data', $message);
        $this->assertArrayHasKey('debug', $message['data']);
        $this->assertArrayHasKey('recurrentable', $message);
    }

    /**
     * @test
     */
    public function it_can_be_get_data_to_json(): void
    {
        $this->builder
            ->amount(self::AMOUNT)
            ->orderId(self::ORDER_ID)
            ->showOrderId(self::SHOW_ORDER_ID)
            ->paymentMethod(self::PAYMENT_METHOD)
            ->description(self::DETAILS)
            ->successUrl(self::SUCCESS_URL)
            ->failUrl(self::FAIL_URL)
            ->customerAccount(self::CUSTOMER_ACCOUNT)
            ->customerEmail(self::CUSTOMER_EMAIL)
            ->customerPhone(self::CUSTOMER_PHONE)
            ->customerComment(self::CUSTOMER_COMMENT)
            ->displayLanguage(self::DISPLAY_LANGUAGE)
            ->recurrentable(self::RECURRENTABLE)
            ->data(self::DATA)
            ->setNonce();

        $json = $this->builder->toJson();

        $this->assertJson($json);
    }

    /**
     * @test
     */
    public function it_can_be_init_config_params(): void
    {
        $data = $this->builder->toArray();

        $this->assertIsArray($data);
        $this->assertNotEmpty($data);
        $this->assertEquals(config('payment.pscb.successUrl'), $data['successUrl']);
        $this->assertEquals(config('payment.pscb.failUrl'), $data['failUrl']);
        $this->assertEquals(config('payment.pscb.displayLanguage'), $data['displayLanguage']);
    }

    /**
     * @test
     */
    public function it_can_be_set_customer_data(): void
    {
        $data = $this->builder->customer([
            'account' => self::CUSTOMER_ACCOUNT,
            'phone' => self::CUSTOMER_PHONE,
            'email' => self::CUSTOMER_EMAIL,
        ])->toArray();

        $this->assertIsArray($data);
        $this->assertNotEmpty($data);
        $this->assertEquals(self::CUSTOMER_ACCOUNT, $data['customerAccount']);
        $this->assertEquals(self::CUSTOMER_EMAIL, $data['customerEmail']);
        $this->assertEquals(self::CUSTOMER_PHONE, $data['customerPhone']);
    }

    /**
     * @test
     */
    public function it_can_be_build_from_data_array(): void
    {
        $data = [
            'amount' => self::AMOUNT,
            'orderId' => self::ORDER_ID
        ];

        $query = $this->builder->setParams($data);

        $this->assertArrayHasKey('amount', $query->toArray());
        $this->assertArrayHasKey('orderId', $query->toArray());
    }

    /**
     * @test
     */
    public function it_can_be_get_pay_url(): void
    {
        $url = $this->builder
            ->amount(self::AMOUNT)
            ->orderId(self::ORDER_ID)->getPayUrl();

        $this->assertIsString($url);
        $this->assertIsUrl($url);
    }

    /**
     * @test
     */
    public function it_can_be_get_pay_url_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->builder->getPayUrl();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->builder = new PscbQueryBuilder;
    }
}
