<?php

namespace CoffeeRun\Bank;

use CoffeeRun\UserId;

final class AccountsInMemory implements Accounts
{
    private $log = array();

    public function log(UserId $id, $event)
    {
        $id = (string) $id;

        if (!isset($this->log[$id])) {
            $this->log[$id] = array();
        }

        $this->log[$id][] = $event;
    }

    public function reduce(UserId $id, $function)
    {
        $id = (string) $id;

        return array_reduce(
            $this->log[$id],
            $function
        );
    }
}
