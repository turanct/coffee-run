<?php

namespace CoffeeRun\Bank;

use CoffeeRun\UserId;

interface Accounts
{
    public function log(UserId $id, $event);
    public function reduce(UserId $id, $function);
}
