<?php

namespace CoffeeRun\Runs;

interface Products
{
    /**
     * Get a Product by its id
     *
     * @param ProductId $id The product's id
     *
     * @throws \InvalidArgumentException when the product is not found
     *
     * @return Product A product
     */
    public function getById(ProductId $id);

    /**
     * Get a list of Products available in a shop
     *
     * @param ShopId $shopId The shop's id
     *
     * @return Product[] A list of products
     */
    public function forShop(ShopId $shopId);
}
