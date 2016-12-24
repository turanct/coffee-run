<?php

namespace CoffeeRun\Runs;

use InvalidArgumentException;

final class Description
{
    private $description;

    /**
     * Description
     *
     * @param string $description Extra info on an order
     */
    public function __construct($description)
    {
        $this->assertText($description);

        $this->description = $description;
    }

    private function assertText($description)
    {
        if (!is_string($description)) {
            $message = "Description should be a string, '{$description}' given";

            throw new InvalidArgumentException($message);
        }
    }
}
