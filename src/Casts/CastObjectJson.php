<?php

namespace Fh\PaymentManager\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Eloquent\Model;

abstract class CastObjectJson implements CastsAttributes, Arrayable, Jsonable
{
    /**
     * @var array
     */
    private $attributes;

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
        foreach ($this->attributes as $field => $value) {
            $this->{$field} = $value;
        }
    }

    /**
     * @param Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes)
    {
        return $value ? new static(json_decode($value, true)) : null;
    }

    /**
     * @param Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return false|mixed|string
     */
    public function set($model, string $key, $value, array $attributes)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param int $options
     * @return false|string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->attributes;
    }
}
