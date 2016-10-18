<?php

namespace CoffeeRun;

final class Product
{
    private $id;
    private $name;
    private $price;

    public function __construct(ShopId $id, $name, Price $price)
    {
        $this->id = $id;
        $this->name = (string) $name;
        $this->price = $price;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPrice()
    {
        return $this->price;
    }
}
