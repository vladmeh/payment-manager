<?php


namespace Fh\PaymentManager\Tests\Fixtures;


use Fh\PaymentManager\Contracts\Buyable;
use Illuminate\Database\Eloquent\Model;

class BuyableProduct implements Buyable
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $price;


    /**
     * BayableProduct constructor.
     */
    public function __construct($id = 1, $name = 'Item name', $price = 100)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
    }

    public function getBuyableIdentifier(array $options = [])
    {
        return $this->id;
    }

    public function getBuyableDescription(array $options = []): string
    {
        return $this->name;
    }

    public function getBuyablePrice(array $options = []): int
    {
        return $this->price;
    }
}