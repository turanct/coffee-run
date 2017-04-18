<?php

namespace CoffeeRun\Bank;

final class TransactionLogInMemory implements TransactionLog
{
    private $log = array();

    public function append($event)
    {
        $this->log[] = $event;
    }

    public function reduce($function)
    {
        return array_reduce(
            $this->log,
            $function
        );
    }
}
