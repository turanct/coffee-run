<?php

namespace CoffeeRun\Runs;

interface Shops
{
    /**
     * Get a list of all shops
     *
     * @return Shop[] A list of shops
     */
    public function getAll();

    /**
     * Get a shop by its id
     *
     * @param ShopId The shop's id
     *
     * @return Shop The shop
     */
    public function getById(ShopId $id);
}
