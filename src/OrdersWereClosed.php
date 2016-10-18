<?php

namespace CoffeeRun;

use DateTime;

final class OrdersWereClosed
{
    private $id;
    private $createdAt;

    public function __construct(CoffeeRunId $id, DateTime $createdAt)
    {
        $this->id = $id;
        $this->createdAt = $createdAt;
    }

    public function getCoffeeRunId()
    {
        return $this->id;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
