<?php

namespace CoffeeRun;

use DateTime;

final class ProductWasOrdered
{
    private $id;
    private $userId;
    private $productId;
    private $createdAt;

    public function __construct(
        CoffeeRunId $id,
        UserId $userId,
        ProductId $productId
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->productId = $productId;
        $this->createdAt = new DateTime('now');
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getProductId()
    {
        return $this->productId;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
