<?php

namespace CoffeeRun\Bank;

use InvalidArgumentException;

final class Money
{
    private $amount;

    /**
     * Money
     *
     * An amount of money, in cents: we'll calculate using the maximum
     * precision that we want to use throughout the application, let's
     * say 1 cent. We'll try to hold off on currencies for now, let's
     * not make it more complicated that we need. Everything is in the
     * same currency.
     *
     * @param int $amount
     */
    public function __construct($amount)
    {
        $this->assertInteger($amount);

        $this->amount = $amount;
    }

    private function assertInteger($amount)
    {
        if (!is_int($amount)) {
            $message = "Money should be an integer, '{$amount}' given";

            throw new InvalidArgumentException($message);
        }
    }

    public function add(Money $otherMoney)
    {
        return new static($this->amount + $otherMoney->amount);
    }

    public function subtract(Money $otherMoney)
    {
        return new static($this->amount - $otherMoney->amount);
    }

    public function greaterThan(Money $otherMoney)
    {
        return $this->amount > $otherMoney->amount;
    }
}
