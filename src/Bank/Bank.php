<?php

namespace CoffeeRun\Bank;

use CoffeeRun\UserId;
use CoffeeRun\Clock;

final class Bank
{
    private $clock;
    private $log;

    public function __construct(Clock $clock, TransactionLog $log)
    {
        $this->clock = $clock;
        $this->log = $log;
    }

    public function lendMoney(UserId $from, UserId $to, Money $amount, $reason)
    {
        $time = $this->clock->getTime();
        $event = new MoneyWasLended($from, $to, $amount, $reason, $time);

        $this->log->append($event);
    }

    public function pay(UserId $from, UserId $to, Money $amount, $reason)
    {
        $time = $this->clock->getTime();
        $event = new MoneyWasPaid($from, $to, $amount, $reason, $time);

        $this->log->append($event);
    }

    public function paymentsToBeMadeBy(UserId $by)
    {
        $balances = $this->log->reduce(
            function ($balances, $event) use ($by) {
                $by = (string) $by;
                $from = (string) $event->getfrom();
                $to = (string) $event->getTo();
                $amount = $event->getAmount();

                if ($event instanceof MoneyWasLended
                    && $from == $by
                ) {
                    if (!isset($balances[$to])) {
                        $balances[$to] = new Money(0);
                    }

                    $balances[$to] = $balances[$to]->subtract($amount);
                }

                if ($event instanceof MoneyWasLended
                    && $to == $by
                ) {
                    if (!isset($balances[$from])) {
                        $balances[$from] = new Money(0);
                    }

                    $balances[$from] = $balances[$from]->add($amount);
                }

                if ($event instanceof MoneyWasPaid
                    && $from == $by
                ) {
                    if (!isset($balances[$to])) {
                        $balances[$to] = new Money(0);
                    }

                    $balances[$to] = $balances[$to]->subtract($amount);
                }

                if ($event instanceof MoneyWasPaid
                    && $to == $by
                ) {
                    if (!isset($balances[$from])) {
                        $balances[$from] = new Money(0);
                    }

                    $balances[$from] = $balances[$from]->add($amount);
                }

                return $balances;
            }
        );

        $payments = array_filter(
            $balances,
            function (Money $amount) {
                return $amount->greaterThan(new Money(0));
            }
        );

        $payments = array_map(
            function ($amount, $to) use ($by) {
                return new DuePayment($by, new UserId($to), $amount);
            },
            $payments,
            array_keys($payments)
        );

        return $payments;
    }

    public function paymentsToBeReceivedBy(UserId $by)
    {
        $balances = $this->log->reduce(
            function ($balances, $event) use ($by) {
                $by = (string) $by;
                $from = (string) $event->getfrom();
                $to = (string) $event->getTo();
                $amount = $event->getAmount();

                if ($event instanceof MoneyWasLended
                    && $from == $by
                ) {
                    if (!isset($balances[$to])) {
                        $balances[$to] = new Money(0);
                    }

                    $balances[$to] = $balances[$to]->add($amount);
                }

                if ($event instanceof MoneyWasLended
                    && $to == $by
                ) {
                    if (!isset($balances[$from])) {
                        $balances[$from] = new Money(0);
                    }

                    $balances[$from] = $balances[$from]->subtract($amount);
                }

                if ($event instanceof MoneyWasPaid
                    && $from == $by
                ) {
                    if (!isset($balances[$to])) {
                        $balances[$to] = new Money(0);
                    }

                    $balances[$to] = $balances[$to]->add($amount);
                }

                if ($event instanceof MoneyWasPaid
                    && $to == $by
                ) {
                    if (!isset($balances[$from])) {
                        $balances[$from] = new Money(0);
                    }

                    $balances[$from] = $balances[$from]->subtract($amount);
                }

                return $balances;
            }
        );

        $payments = array_filter(
            $balances,
            function (Money $amount) {
                return $amount->greaterThan(new Money(0));
            }
        );

        $payments = array_map(
            function ($amount, $to) use ($by) {
                return new DuePayment(new UserId($to), $by, $amount);
            },
            $payments,
            array_keys($payments)
        );

        return $payments;
    }
}
