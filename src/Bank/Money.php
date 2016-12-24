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
            $message = "Money should be a positive integer, '{$amount}' given";

            throw new InvalidArgumentException($message);
        }
    }

    public function add(Money $otherMoney)
    {
        return new static($this->amount + $otherMoney->amount);
    }

    public function subtract(Money $otherMoney)
    {
        if ($otherMoney->greaterThan($this)) {
            return new static(0);
        }

        return new static($this->amount - $otherMoney->amount);
    }

    public function greaterThan(Money $otherMoney)
    {
        return $this->amount > $otherMoney->amount;
    }
}
