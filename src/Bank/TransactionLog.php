<?php

namespace CoffeeRun\Bank;

interface TransactionLog
{
    public function append($event);
    public function reduce($function);
}
