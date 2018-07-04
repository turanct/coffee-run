<?php

namespace CoffeeRun\Runs;

interface CoffeeRuns
{
    /**
     * Get a list of coffeeruns open for orders
     *
     * @return CoffeeRun[] A list of coffeeruns
     */
    public function openForOrders();

    /**
     * Get a coffeerun by its id
     *
     * @param CoffeeRunId $id The coffeerun's id
     *
     * @return CoffeeRun The coffeerun
     */
    public function getById(CoffeeRunId $id);

    /**
     * Persist a coffeerun
     *
     * @param CoffeeRun $coffeeRun The coffeerun instance
     */
    public function persist(CoffeeRun $coffeeRun);
}
