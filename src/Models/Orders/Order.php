<?php


namespace Fh\PaymentManager\Models\Orders;


use Fh\PaymentManager\Contracts\Buyable;
use Illuminate\Support\Collection;

class Order
{
    const DEFAULT_INSTANCE = 'default';

    /**
     * @var string
     */
    private $instance;

    public function __construct()
    {
        $this->instance(self::DEFAULT_INSTANCE);
    }

    /**
     * @param string $instance
     * @return $this
     */
    public function instance(string $instance = ''): Order
    {
        $instance = $instance ?: self::DEFAULT_INSTANCE;
        $this->instance = sprintf('%s.%s', 'order', $instance);

        return $this;
    }

    public function currentInstance()
    {
        return str_replace('order.', '', $this->instance);
    }


    /**
     * @param mixed $id
     * @param mixed $name
     * @param int|null $qty
     * @param int|null $price
     * @param array $options
     */
    public function addItem($id,
                            $name = null,
                            int $qty = null,
                            int $price = null,
                            array $options = [])
    {
        if ($this->isMultiple($id)) {
            return array_map(function ($item) {
                return $this->addItem($item);
            }, $id);
        }

        $orderItem = $this->createOrderItem($id, $name, $qty, $price, $options);

        $content = $this->getContent();

        if ($content->has($orderItem->rowId)) {
            $orderItem->qty += $content->get($orderItem->rowId)->qty;
        }

        $content->put($orderItem->rowId, $orderItem);

        session()->put($this->instance, $content);

        return $orderItem;
    }

    /**
     * @param mixed $item
     * @return bool
     */
    private function isMultiple($item): bool
    {
        if (!is_array($item)) {
            return false;
        }

        return is_array(head($item)) || head($item) instanceof Buyable;
    }

    /**
     * @param mixed $id
     * @param mixed $name
     * @param int|null $qty
     * @param int|null $price
     * @param array $options
     * @return OrderItem
     */
    private function createOrderItem($id, $name, ?int $qty, ?int $price, array $options): OrderItem
    {
        if ($id instanceof Buyable) {
            $orderItem = OrderItem::fromBuyable($id, $qty ?: []);
            $orderItem->setQuantity($name ?: 1);
            $orderItem->associate($id);
        } elseif (is_array($id)) {
            $orderItem = OrderItem::fromArray($id);
            $orderItem->setQuantity($id['qty']);
        } else {
            $orderItem = OrderItem::fromAttributes($id, $name, $price, $options);
            $orderItem->setQuantity($qty);
        }

        return $orderItem;
    }

    /**
     * @return Collection|mixed
     */
    private function getContent()
    {
        return session()->has($this->instance)
            ? session()->get($this->instance)
            : new Collection;
    }

    /**
     * @param string $rowId
     * @return OrderItem
     */
    public function getItem(string $rowId): OrderItem
    {
        $content = $this->getContent();

        if (!$content->has($rowId)) {
            throw new \RuntimeException("The cart does not contain rowId {$rowId}.");

        }

        return $content->get($rowId);
    }

    /**
     * @return Collection
     */
    public function content(): Collection
    {
        if (is_null(session()->get($this->instance))) {
            return new Collection([]);
        }
        return session()->get($this->instance);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        $content = $this->getContent();
        return $content->sum('qty');
    }
}