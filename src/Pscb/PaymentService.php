<?php


namespace Vladmeh\PaymentManager\Pscb;


class PaymentService
{
    /**
     * Создание данных для запроса создания платежа в ПСКБ
     *
     * @param PaymentOrder $order
     * @param PaymentCustomer $customer
     * @param array $requestParameters
     * @return PaymentRequestData
     */
    public function createDataPayment(PaymentOrder $order, PaymentCustomer $customer, array $requestParameters = []): PaymentRequestData
    {
        return PaymentRequestData::make($order->getAmount(), $order->getOrderId(), $requestParameters)
            ->setCustomerAccount($customer->getAccount())
            ->setCustomerEmail($customer->getEmail())
            ->setCustomerPhone($customer->getPhone())
            ->setCustomerComment($customer->getComment());
    }
}