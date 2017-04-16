<?php

namespace CoffeeRun;

use DateTime;

final class ClockServerTime implements Clock
{
    public function getTime()
    {
        return new DateTime('now');
    }
}
