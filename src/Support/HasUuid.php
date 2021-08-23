<?php

namespace Fh\PaymentManager\Support;

use Ramsey\Uuid\Uuid;

/**
 * @link https://github.com/binarycabin/laravel-uuid, https://tighten.co/blog/laravel-tip-bootable-model-traits/
 *
 * @method static byUuid($uuid)
 * @method static creating(\Closure $param)
 */
trait HasUuid
{
    public static function bootHasUuid()
    {
        static::creating(function ($model) {
            $uuidFieldName = $model->getUuidFieldName();
            if (empty($model->$uuidFieldName)) {
                $model->$uuidFieldName = static::generateUuid();
            }
        });
    }

    /**
     * @return string
     */
    public static function generateUuid(): string
    {
        return Uuid::uuid4()->toString();
    }

    /**
     * @return string
     */
    public function getUuidFieldName(): string
    {
        if (!empty($this->uuidFieldName)) {
            return $this->uuidFieldName;
        }

        return 'uuid';
    }
}