<?php

namespace CoffeeRun\Runs;

use CoffeeRun\UserId;

final class Order
{
    private $userId;
    private $productId;
    private $price;

    public function __construct(
        UserId $userId,
        ProductId $productId,
        Price $price,
        Description $description
    ) {
        $this->userId = $userId;
        $this->productId = $productId;
        $this->price = $price;
        $this->description = $description;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getProductId()
    {
        return $this->productId;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getDescription()
    {
        return $this->description;
    }
}
