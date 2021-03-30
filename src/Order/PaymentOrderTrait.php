<?php

namespace Vladmeh\PaymentManager\Order;

use Vladmeh\PaymentManager\Contracts\PaymentCustomer;
use Vladmeh\PaymentManager\Pscb\PaymentStatus;

/**
 * @property int amount
 * @property string uuid
 */
trait PaymentOrderTrait
{
    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getOrderId(): string
    {
        return $this->uuid;
    }

    /**
     * @param PaymentCustomer $customer
     * @return void
     */
    public function setCustomer(PaymentCustomer $customer)
    {
        $this->customer()
            ->associate($customer)
            ->save();
    }

    /**
     * @param mixed $payment
     * @return void
     */
    public function setPayment($payment)
    {
        if (is_array($payment) && array_key_exists('payment', $payment)) {
            $paymentStatus = $payment['payment']['state']
                ? PaymentStatus::status($payment['payment']['state'])
                : PaymentStatus::UNDEF;

            $this->update([
                'payment' => $payment['payment'],
                'state' => $paymentStatus
            ]);
        }
    }
}
