<?php

namespace CoffeeRun\Runs;

final class ProductId
{
    private $id;

    public function __construct($id)
    {
        $this->id = (string) $id;
    }

    public static function generate()
    {
        $id = uniqid('productid-', true);

        return new static($id);
    }

    public function __toString()
    {
        return $this->id;
    }
}
