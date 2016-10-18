<?php

namespace CoffeeRun;

interface Clock
{
    /**
     * Read the current time from the clock
     *
     * @return DateTime
     */
    public function getTime();
}
