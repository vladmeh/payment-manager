<?php

declare(strict_types=1);

namespace Fh\PaymentManager\Pscb;

use Fh\PaymentManager\Support\StatusTrait;

class PaymentStatus
{
    use StatusTrait;

    const NEW = 'новый';
    const SENT = 'в оплате';
    const END = 'оплачен';
    const REF = 'возвращён';
    const EXP = 'просрочен';
    const HOLD = 'холд';
    const CANCELED = 'отменен';
    const ERR = 'ошибка';
    const REJ = 'отвергнут';
    const UNDEF = 'не определен';

    const FINAL_STATE = [
        self::END,
        self::REF,
        self::EXP,
        self::CANCELED,
        self::ERR,
        self::REJ
    ];

    const STATUS = [
        'new' => self::NEW,
        'sent' => self::SENT,
        'end' => self::END,
        'ref' => self::REF,
        'exp' => self::EXP,
        'hold' => self::HOLD,
        'canceled' => self::CANCELED,
        'err' => self::ERR,
        'rej' => self::REJ,
        'undef' => self::UNDEF
    ];

    const ACTION_CONFIRM = 'CONFIRM';
    const ACTION_REJECT = 'REJECT';

    /**
     * @param string $state
     * @return bool
     */
    public static function isFinalState(string $state): bool
    {
        return in_array($state, self::FINAL_STATE)
            || in_array(self::status($state), self::FINAL_STATE);
    }
}
