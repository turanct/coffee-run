<?php

namespace CoffeeRun;

final class DuePayment
{
    private $from;
    private $to;
    private $amount;

    public function __construct(UserId $from, UserId $to, Price $amount)
    {
        $this->from = $from;
        $this->to = $to;
        $this->amount = $amount;
    }
}
