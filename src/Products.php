<?php

namespace CoffeeRun;

interface Products
{
    /**
     * Get a list of Products available in a shop
     *
     * @param ShopId The shop's id
     *
     * @return Product[] A list of products
     */
    public function forShop(ShopId $id);
}
