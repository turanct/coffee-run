<?php

namespace CoffeeRun;

use DateTime;

class BankTest extends \PHPUnit_Framework_TestCase
{
    public function test_i_can_ask_money_from_someone()
    {
        $bank = new Bank($this->getClock(new DateTime('now')));

        $from = UserId::generate();
        $to = UserId::generate();
        $amount = new Price(100);

        $bank->expectPayment($from, $to, $amount);

        $this->assertEquals(
            array(
                new DuePayment($from, $to, $amount),
            ),
            $bank->paymentsToBeMade($from)
        );

        $bank->expectPayment($from, $to, $amount);

        $this->assertEquals(
            array(
                new DuePayment($from, $to, $amount->add($amount)),
            ),
            $bank->paymentsToBeMade($from)
        );
    }

    public function test_paying_someone_money_pays_back_debt()
    {
        $bank = new Bank($this->getClock(new DateTime('now')));

        $from = UserId::generate();
        $to = UserId::generate();
        $amount = new Price(100);

        $bank->expectPayment($from, $to, $amount);
        $bank->pay($from, $to, $amount);

        $this->assertEquals(
            array(),
            $bank->paymentsToBeMade($from)
        );

        $bank->pay($from, $to, $amount);

        $this->assertEquals(
            array(),
            $bank->paymentsToBeMade($from)
        );
    }

    private function getClock(DateTime $now)
    {
        $clock = $this->getMock('CoffeeRun\\Clock');
        $clock
            ->method('getTime')
            ->willReturn($now)
        ;

        return $clock;
    }
}
