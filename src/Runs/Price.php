<?php

namespace CoffeeRun\Runs;

use InvalidArgumentException;

final class Price
{
    private $amount;

    /**
     * Price
     *
     * A price, in cents: we'll calculate using the maximum precision
     * that we want to use throughout the application, let's say 1 cent.
     * We'll try to hold off on currencies for now, let's not make it
     * more complicated that we need. Everything is in the same currency.
     *
     * @param string $amount
     */
    public function __construct($amount)
    {
        $this->assertPositiveInteger($amount);

        $this->amount = $amount;
    }

    private function assertPositiveInteger($amount)
    {
        if (!is_int($amount) || $amount < 0) {
            $message = "Price should be a positive integer, '{$amount}' given";

            throw new InvalidArgumentException($message);
        }
    }

    public function add(Price $otherPrice)
    {
        return new static($this->amount + $otherPrice->amount);
    }

    public function subtract(Price $otherPrice)
    {
        if ($otherPrice->greaterThan($this)) {
            return new static(0);
        }

        return new static($this->amount - $otherPrice->amount);
    }

    public function greaterThan(Price $otherPrice)
    {
        return $this->amount > $otherPrice->amount;
    }
}
