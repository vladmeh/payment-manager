<?php


namespace Vladmeh\PaymentManager\Pscb;


/**
 * объект с набором параметров созданного платежа;
 * @link https://docs.pscb.ru/oos/api.html#api-dopolnitelnyh-vozmozhnostej-shema-vzaimodejstviya-otvet
 *
 * @package Vladmeh\PaymentManager\Pscb
 */
class Payment
{
    /**
     * Уникальный идентификатор платежа на стороне Магазина.
     *
     * @var int
     */
    private $orderId;

    /**
     * Идентификатор платежа, отображаемый Плательщику.
     *
     * @var string
     */
    private $showOrderId;

    /**
     * Уникальный идентификатор платежа в Системе.
     *
     * @var string
     */
    private $paymentId;

    /**
     * Уникальный идентификатор Плательщика на стороне Мерчанта.
     *
     * @var string
     */
    private $account;

    /**
     * Сумма платежа в рублях.
     *
     * @var int
     */
    private $amount;

    /**
     * Статус платежа. Возможные значения:
     * см. @link https://docs.pscb.ru/oos/index.html#obshaya-informaciya-spravochniki-statusy-platezhej
     *
     * @var string
     */
    private $state;

    /**
     * Подсостояние платежа.
     * Возможное значение: hold_confirmed - холд подтверждён.
     * Содержится в ответе только для подтверждённых платежей в статусе hold.
     *
     * @var string
     */
    private $subState;

    /**
     * Идентификатор Магазина.
     *
     * @var string
     */
    private $marketPlace;

    /**
     * Платёжный метод.
     *
     * @var string
     */
    private $paymentMethod;

    /**
     * Дата присвоения текущего статуса платежа в формате ISO8601.
     *
     * @var string
     */
    private $stateDate;

    /**
     * Токен для повторных (рекуррентных) платежей.
     * Содержится в ответе только для родительских рекуррентных платежей.
     *
     * @var string
     */
    private $recurrencyToken;

    /**
     * Контактный e-mail Плательщика.
     *
     * @var string
     */
    private $email;

    /**
     * Контактный телефон Плательщика в международном формате.
     *
     * @var string
     */
    private $phone;

    /**
     * Комментарий Плательщика.
     *
     * @var string
     */
    private $comment;

    /**
     * Детали платежа.
     *
     * @var string
     */
    private $details;
}