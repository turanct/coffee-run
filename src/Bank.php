<?php

namespace CoffeeRun;

final class Bank
{
    private $log = array();
    private $clock;

    public function __construct(Clock $clock)
    {
        $this->clock = $clock;
    }

    public function expectPayment(UserId $from, UserId $to, Price $amount)
    {
        $this->log[] = new PaymentExpected(
            $from,
            $to,
            $amount,
            $this->clock->getTime()
        );
    }

    public function pay(UserId $from, UserId $to, Price $amount)
    {
        $this->log[] = new PaymentReceived(
            $from,
            $to,
            $amount,
            $this->clock->getTime()
        );
    }

    public function paymentsToBeMade(UserId $by)
    {
        $expectedPayments = array_filter(
            $this->log,
            function($logItem) {
                return $logItem instanceof PaymentExpected;
            }
        );

        $payments = array_reduce(
            $expectedPayments,
            function($payments, $logItem) use ($by) {
                if ($logItem->getFrom() == $by) {
                    $to = (string) $logItem->getTo();

                    if (!isset($payments[$to])) {
                        $payments[$to] = $logItem->getAmount();
                    } else {
                        $payments[$to] = $logItem->getAmount()->add(
                            $payments[$to]
                        );
                    }
                }

                return $payments;
            },
            array()
        );

        $receivedPayments = array_filter(
            $this->log,
            function($logItem) {
                return $logItem instanceof PaymentReceived;
            }
        );

        $payments = array_reduce(
            $receivedPayments,
            function($payments, $logItem) use ($by) {
                if ($logItem->getFrom() == $by) {
                    $to = (string) $logItem->getTo();

                    if (isset($payments[$to])) {
                        $payments[$to] = $payments[$to]->subtract(
                            $logItem->getAmount()
                        );
                    }
                }

                return $payments;
            },
            $payments
        );

        $payments = array_filter(
            $payments,
            function(Price $amount) {
                return $amount->greaterThan(new Price(0));
            }
        );

        $payments = array_map(
            function($amount, $to) use ($by) {
                return new DuePayment($by, new UserId($to), $amount);
            },
            $payments,
            array_keys($payments)
        );

        return $payments;
    }

    public function paymentsToBeReceived(UserId $by)
    {
        $expectedPayments = array_filter(
            $this->log,
            function($logItem) {
                return $logItem instanceof PaymentExpected;
            }
        );

        $payments = array_reduce(
            $expectedPayments,
            function($payments, $logItem) use ($by) {
                if ($logItem->getTo() == $by) {
                    $from = (string) $logItem->getFrom();

                    if (!isset($payments[$from])) {
                        $payments[$from] = $logItem->getAmount();
                    } else {
                        $payments[$from] = $logItem->getAmount()->add(
                            $payments[$from]
                        );
                    }
                }

                return $payments;
            },
            array()
        );

        $receivedPayments = array_filter(
            $this->log,
            function($logItem) {
                return $logItem instanceof PaymentReceived;
            }
        );

        $payments = array_reduce(
            $receivedPayments,
            function($payments, $logItem) use ($by) {
                if ($logItem->getTo() == $by) {
                    $from = (string) $logItem->getFrom();

                    if (isset($payments[$from])) {
                        $payments[$from] = $payments[$from]->subtract(
                            $logItem->getAmount()
                        );
                    }
                }

                return $payments;
            },
            $payments
        );

        $payments = array_filter(
            $payments,
            function(Price $amount) {
                return $amount->greaterThan(new Price(0));
            }
        );

        $payments = array_map(
            function($amount, $from) use ($by) {
                return new DuePayment(new UserId($from), $by, $amount);
            },
            $payments,
            array_keys($payments)
        );

        return $payments;
    }
}
