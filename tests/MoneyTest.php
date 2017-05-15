<?php

namespace CoffeeRun\Bank;

class MoneyTest extends \PHPUnit_Framework_TestCase
{
    public function test_it_can_be_added_to_another_amount()
    {
        $moneyA = new Money(100);
        $moneyB = new Money(300);

        $this->assertEquals(
            new Money(500),
            $moneyA->add($moneyB)
        );
    }

    public function test_it_can_be_subtracted_from_another_amount()
    {
        $moneyA = new Money(100);
        $moneyB = new Money(300);

        $this->assertEquals(
            new Money(200),
            $moneyB->subtract($moneyA)
        );
    }

    public function test_it_can_be_compared_to_another_amount()
    {
        $moneyA = new Money(100);
        $moneyB = new Money(300);

        $this->assertEquals(
            false,
            $moneyA->greaterThan($moneyB)
        );

        $this->assertEquals(
            true,
            $moneyB->greaterThan($moneyA)
        );
    }
}
