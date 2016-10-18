<?php

namespace CoffeeRun;

use DateTime;

final class CoffeeRunWasAnnounced
{
    private $id;
    private $userId;
    private $shopId;
    private $time;
    private $createdAt;

    public function __construct(
        CoffeeRunId $id,
        UserId $userId,
        ShopId $shopId,
        DateTime $time,
        DateTime $createdAt
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->shopId = $shopId;
        $this->time = clone $time;
        $this->createdAt = $createdAt;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getShopId()
    {
        return $this->shopId;
    }

    public function getTime()
    {
        return $this->time;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
