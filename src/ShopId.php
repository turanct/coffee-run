<?php

namespace CoffeeRun;

final class ShopId
{
    private $id;

    public function __construct($id)
    {
        $this->id = (string) $id;
    }

    public static function generate()
    {
        $id = uniqid('shopid-', true);

        return new static($id);
    }

    public function __toString()
    {
        return $this->id;
    }
}
