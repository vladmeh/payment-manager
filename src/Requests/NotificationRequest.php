<?php
declare(strict_types=1);

namespace Vladmeh\PaymentManager\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Vladmeh\PaymentManager\Events\ConfirmationOrderEvent;
use Vladmeh\PaymentManager\Models\Order;
use Vladmeh\PaymentManager\Pscb\PaymentStatus;

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
     */
    private function confirmPayment(array $paymentData): string
    {
        if (!$order = Order::find($paymentData['orderId'])) {
            Log::channel('payment')->info('Платеж отвергнут: ' . json_encode($paymentData, JSON_UNESCAPED_UNICODE) . ' Не найден заказ.');
            return PaymentStatus::ACTION_REJECT;
        }

        if (!$order->customer || $order->customer->account != $paymentData['account']) {
            Log::channel('payment')->info('Платеж отвергнут: ' . json_encode($paymentData, JSON_UNESCAPED_UNICODE) . ' Не найден клиент.');
            return PaymentStatus::ACTION_REJECT;
        }

        event(new ConfirmationOrderEvent($order, $paymentData));

        return PaymentStatus::ACTION_CONFIRM;
    }
}
