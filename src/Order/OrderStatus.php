<?php


namespace Vladmeh\PaymentManager\Order;


use Vladmeh\PaymentManager\Support\StatusTrait;

class OrderStatus
{
    use StatusTrait;

    /**
     * Создан новый заказ,
     * еще не отправлен на обработку в платежную систему,
     * платежная система о заказе ничего не знает
     */
    const CREATE = 'создан';

    /**
     * Заказ отменен.
     * Заказ отменен по инициативе клиента.
     */
    const CANCELED = 'отменен';

    /**
     * Заказ отправлен в платежную систему на обработку.
     * Ответ от платежной системы о состоянии платежа не получен.
     */
    const SENT = 'отравлен в ПС';

    /**
     * Заказ принят к оплате и обрабатывается платежной системой.
     * По запросу от платежной системы получет ответ со
     * статусом промежуточного состояния платежа.
     */
    const PROCESSING = 'обрабатывается ПС';

    /**
     * Заказ обработан ПС.
     * По запросу от платежной системы получет ответ со
     * статусом конечного состояния платежа
     * (оплачен, возвращен, просрочен, отменен, ошибка, отвергнут).
     */
    const TREATED = 'обработан ПС';

    /**
     * Заказ в ПС не найден
     * По запросу к платежной системе получет ответ
     * со статусом STATUS_FAILURE, errorCode = UNKNOWN_PAYMENT
     */
    const NOT_FOUND = 'не найден в ПС';

    /**
     * Ошибка запроса к ПС
     * По запросу к платежной системе получет ответ
     * со статусом STATUS_FAILURE, errorCode != UNKNOWN_PAYMENT
     */
    const ERROR = 'error';

    /**
     * Заказ закрыт.
     * Заказ оплачен в ПС и разнесен по базам, на товары в заказе
     * получены договора (контракты)
     */
    const CLOSE = 'закрыт';

    const STATUS = [
        'create' => self::CREATE,
        'sent' => self::SENT,
        'processing' => self::PROCESSING,
        'not_found' => self::NOT_FOUND,
        'treated' => self::TREATED,
        'close' => self::CLOSE,
        'error' => self::ERROR,
        'canceled' => self::CANCELED
    ];

}
