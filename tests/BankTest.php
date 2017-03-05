<?php

namespace CoffeeRun\Bank;

use CoffeeRun\UserId;
use DateTime;

class BankTest extends \PHPUnit_Framework_TestCase
{
    public function test_lending_someone_money()
    {
        $accounts = new AccountsInMemory;
        $bank = new Bank(
            $this->getClock(new DateTime('now')),
            $accounts
        );

        $userA = UserId::generate();
        $userB = UserId::generate();
        $amount = new Money(100);
        $reason = 'foo';

        $bank->lendMoney($userA, $userB, $amount, $reason);

        $this->assertEquals(
            array(),
            $bank->paymentsToBeMadeBy($userA)
        );

        $this->assertEquals(
            array(
                new DuePayment($userB, $userA, $amount),
            ),
            $bank->paymentsToBeMadeBy($userB)
        );

        $this->assertEquals(
            array(
                new DuePayment($userB, $userA, $amount),
            ),
            $bank->paymentsToBeReceivedBy($userA)
        );

        $this->assertEquals(
            array(),
            $bank->paymentsToBeReceivedBy($userB)
        );
    }

    public function test_paying_someone_back()
    {
        $accounts = new AccountsInMemory;
        $bank = new Bank(
            $this->getClock(new DateTime('now')),
            $accounts
        );

        $userA = UserId::generate();
        $userB = UserId::generate();
        $amount = new Money(100);
        $reason = 'foo';

        $bank->lendMoney($userA, $userB, $amount, $reason);
        $bank->pay($userB, $userA, $amount, $reason);

        $this->assertEquals(
            array(),
            $bank->paymentsToBeMadeBy($userA)
        );

        $this->assertEquals(
            array(),
            $bank->paymentsToBeMadeBy($userB)
        );

        $this->assertEquals(
            array(),
            $bank->paymentsToBeReceivedBy($userA)
        );

        $this->assertEquals(
            array(),
            $bank->paymentsToBeReceivedBy($userB)
        );
    }

    public function test_paying_someone_back_too_much()
    {
        $accounts = new AccountsInMemory;
        $bank = new Bank(
            $this->getClock(new DateTime('now')),
            $accounts
        );

        $userA = UserId::generate();
        $userB = UserId::generate();
        $amount = new Money(100);
        $tooMuch = new Money(150);
        $reason = 'foo';

        $bank->lendMoney($userA, $userB, $amount, $reason);
        $bank->pay($userB, $userA, $tooMuch, $reason);

        $this->assertEquals(
            array(
                new DuePayment($userA, $userB, $tooMuch->subtract($amount)),
            ),
            $bank->paymentsToBeMadeBy($userA)
        );

        $this->assertEquals(
            array(),
            $bank->paymentsToBeMadeBy($userB)
        );

        $this->assertEquals(
            array(),
            $bank->paymentsToBeReceivedBy($userA)
        );

        $this->assertEquals(
            array(
                new DuePayment($userA, $userB, $tooMuch->subtract($amount)),
            ),
            $bank->paymentsToBeReceivedBy($userB)
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
