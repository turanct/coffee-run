<?php

namespace CoffeeRun;

use DateTime;

final class OrderWasPlaced
{
    private $id;
    private $order;
    private $createdAt;

    public function __construct(
        CoffeeRunId $id,
        Order $order,
        DateTime $createdAt
    ) {
        $this->id = $id;
        $this->order = $order;
        $this->createdAt = $createdAt;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
