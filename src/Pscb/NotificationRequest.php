<?php

declare(strict_types=1);

namespace Fh\PaymentManager\Pscb;

use Fh\PaymentManager\Events\ConfirmationOrderEvent;
use Fh\PaymentManager\Models\PaymentOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class NotificationRequest extends Request
{
    /**
     * @return void
     * @throws ValidationException
     */
    public function validateDate(): void
    {
        $validator = Validator::make($this->all(), [
            'payments' => ['required', 'array'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator, response('Unprocessable Entity', 422));
        }
    }

    /**
     * @return array
     */
    public function responseData(): array
    {
        $requestData = $this->all();

        return array_map(function ($payment) {
            return [
                'orderId' => $payment['orderId'],
                'action' => $this->confirmPayment($payment)
            ];
        }, $requestData['payments']);
    }

    /**
     * @param array $paymentData
     * @return string
     *
     * @todo Out to object or class
     */
    private function confirmPayment(array $paymentData): string
    {
        if (!$order = PaymentOrder::findById($paymentData['orderId'])) {
            Log::channel('payment')->info('Платеж отвергнут: ' . json_encode($paymentData, JSON_UNESCAPED_UNICODE) . ' Не найден заказ.');

            return PaymentStatus::ACTION_REJECT;
        }

        if (!$order->hasCustomerAccount($paymentData['account'])) {
            Log::channel('payment')->info('Платеж отвергнут: ' . json_encode($paymentData, JSON_UNESCAPED_UNICODE) . ' Не найден клиент.');

            return PaymentStatus::ACTION_REJECT;
        }

        event(new ConfirmationOrderEvent($order, $paymentData));

        return PaymentStatus::ACTION_CONFIRM;
    }
}
