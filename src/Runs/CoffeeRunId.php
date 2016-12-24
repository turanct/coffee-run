<?php

namespace CoffeeRun\Runs;

final class CoffeeRunId
{
    private $id;

    public function __construct($id)
    {
        $this->id = (string) $id;
    }

    public static function generate()
    {
        $id = uniqid('coffeerunid-', true);

        return new static($id);
    }

    public function __toString()
    {
        return $this->id;
    }
}
