<?php

namespace CoffeeRun\Bank;

use CoffeeRun\UserId;
use DateTime;

final class PaymentExpected
{
    private $from;
    private $to;
    private $amount;
    private $createdAt;

    public function __construct(
        UserId $from,
        UserId $to,
        Money $amount,
        DateTime $createdAt
    ) {
        $this->from = $from;
        $this->to = $to;
        $this->amount = $amount;
        $this->createdAt = clone $createdAt;
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function getTo()
    {
        return $this->to;
    }

    public function getAmount()
    {
        return $this->amount;
    }
}
