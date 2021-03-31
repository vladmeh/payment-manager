<?php

namespace Vladmeh\PaymentManager\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Vladmeh\PaymentManager\Contracts\PayableOrder;

class ConfirmationOrderEvent
{
    use Dispatchable, SerializesModels;

    /**
     * @var PayableOrder
     */
    public $order;

    /**
     * @var array
     */
    public $paymentData;

    /**
     * ConfirmationOrderEvent constructor.
     * @param $order
     * @param array $paymentData
     */
    public function __construct(PayableOrder $order, array $paymentData)
    {
        $this->order = $order;
        $this->paymentData = $paymentData;
    }
}
