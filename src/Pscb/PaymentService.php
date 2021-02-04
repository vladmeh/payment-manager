<?php


namespace Vladmeh\PaymentManager\Pscb;


class PaymentService
{
    /**
     * Создание платежа (запрос на создание платежа в ПСКБ)
     *
     * @param PaymentOrder $order
     * @param PaymentCustomer $customer
     * @param array $requestParameters
     * @return PaymentRequest
     */
    public function createPayment(PaymentOrder $order, PaymentCustomer $customer, array $requestParameters = []): PaymentRequest
    {
        return PaymentRequest::make($order->getAmount(), $order->getOrderId(), $requestParameters)
            ->setCustomerAccount($customer->getAccount())
            ->setCustomerEmail($customer->getEmail())
            ->setCustomerPhone($customer->getPhone())
            ->setCustomerComment($customer->getComment());
    }
}