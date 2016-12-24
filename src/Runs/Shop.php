<?php

namespace CoffeeRun\Runs;

final class Shop
{
    private $id;
    private $name;

    public function __construct(ShopId $id, $name)
    {
        $this->id = $id;
        $this->name = (string) $name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }
}
