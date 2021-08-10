<?php


namespace Fh\PaymentManager\Models\Orders;


use Fh\PaymentManager\Contracts\Buyable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Arr;
use Ramsey\Uuid\Nonstandard\Uuid;

class OrderItem implements Arrayable, Jsonable
{
    /**
     * @var int|string
     */
    public $id;

    /**
     * @var string
     */
    public $rowId;

    /**
     * @var string
     */
    public $name;

    /**
     * @var int
     */
    public $price;

    /**
     * @var OrderItemOptions
     */
    public $options;

    /**
     * @var int
     */
    public $qty = 1;

    /**
     * @var string|null
     */
    private $associatedModel = null;


    /**
     * OrderItem constructor.
     */
    public function __construct($id, string $name, int $price, array $options = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->options = new OrderItemOptions($options);

        $this->rowId = $this->generateRowId();
    }

    /**
     * @param Buyable $item
     * @param array $options
     * @return OrderItem
     */
    public static function fromBuyable(Buyable $item, array $options = []): OrderItem
    {
        return new self(
            $item->getBuyableIdentifier($options),
            $item->getBuyableDescription($options),
            $item->getBuyablePrice($options),
            $options
        );
    }

    /**
     * @param array $attributes
     * @return OrderItem
     */
    public static function fromArray(array $attributes): OrderItem
    {
        $options = Arr::get($attributes, 'options', []);

        return new self(
            $attributes['id'],
            $attributes['name'],
            $attributes['price'],
            $options
        );
    }

    /**
     * @param $id
     * @param $name
     * @param $price
     * @param array $options
     * @return OrderItem
     */
    public static function fromAttributes($id, $name, $price, array $options = []): OrderItem
    {
        return new self($id, $name, $price, $options);
    }

    /**
     * @param $model
     * @return OrderItem
     */
    public function associate($model): OrderItem
    {
        $this->associatedModel = is_string($model) ? $model : get_class($model);

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'rowId' => $this->rowId,
            'name' => $this->name,
            'qty' => $this->qty,
            'price' => $this->price,
            'options' => $this->options->toArray()
        ];
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
     * @return string
     */
    public function generateRowId(): string
    {
        return Uuid::uuid6();
    }

    public function __get($attribute)
    {
        if(property_exists($this, $attribute)) {
            return $this->{$attribute};
        }

        if($attribute === 'model' && isset($this->associatedModel)) {
            return with(new $this->associatedModel)->find($this->id);
        }

        return null;
    }

    /**
     * @param int $qty
     */
    public function setQuantity(int $qty)
    {
        $this->qty = $qty;
    }

    public function model()
    {
        return $this->associatedModel;
    }
}