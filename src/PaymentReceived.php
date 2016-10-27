<?php

namespace CoffeeRun;

use DateTime;

final class PaymentReceived
{
    private $from;
    private $to;
    private $amount;
    private $createdAt;

    public function __construct(
        UserId $from,
        UserId $to,
        Price $amount,
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
