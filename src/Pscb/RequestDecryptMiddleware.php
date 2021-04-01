<?php

declare(strict_types=1);

namespace Fh\PaymentManager\Pscb;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RequestDecryptMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // считываем зашифрованное сообщение от ПСКБ из HTTP body
        // $encrypted_request = file_get_contents('php://input');
        $encrypted_message = $request->getContent();

        // расшифровываем сообщение - строка UTF-8
        // Если не можем расшифровать запрос, возвращаем HTTP-код “401 Unauthorized”
        if (!$decrypted_message = PaymentEncrypt::decrypt($encrypted_message)) {
            return response('Unauthorized', 401);
        }
        Log::channel('payment')->info('Оповещение магазина: ' . $decrypted_message);

        $request->replace(json_decode($decrypted_message, true));

        return $next($request);
    }
}
