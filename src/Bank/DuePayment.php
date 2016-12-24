<?php

namespace CoffeeRun\Bank;

use CoffeeRun\UserId;

final class DuePayment
{
    private $from;
    private $to;
    private $amount;

    public function __construct(UserId $from, UserId $to, Money $amount)
    {
        $this->from = $from;
        $this->to = $to;
        $this->amount = $amount;
    }
}
