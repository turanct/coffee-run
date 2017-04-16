<?php

namespace CoffeeRun\Runs;

use CoffeeRun\UserId;
use DateTime;

final class CoffeeRun
{
    private $id;
    private $events;
    private $recordedEvents = array();

    public function __construct(CoffeeRunId $id, array $events)
    {
        $this->id = $id;
        $this->events = $events;
    }

    public static function announce(
        CoffeeRunId $id,
        UserId $userId,
        ShopId $shopId,
        DateTime $time,
        DateTime $currentTime
    ) {
        $events = array();
        $events[] = new CoffeeRunWasAnnounced(
            $id,
            $userId,
            $shopId,
            $time,
            $currentTime
        );

        $coffeeRun = new static($id, $events);
        $coffeeRun->recordedEvents = $events;

        return $coffeeRun;
    }

    public function placeOrder(Order $order, DateTime $currentTime)
    {
        $this->assertOrdersAreOpen();

        $event = new OrderWasPlaced(
            $this->id,
            $order,
            $currentTime
        );

        $this->events[] = $event;
        $this->recordedEvents[] = $event;
    }

    public function stopOrdering(DateTime $currentTime)
    {
        $this->assertOrdersAreOpen();

        $event = new OrdersWereClosed($this->id, $currentTime);

        $this->events[] = $event;
        $this->recordedEvents[] = $event;
    }

    public function ordersCanBeMade()
    {
        foreach ($this->events as $event) {
            if (get_class($event) === 'CoffeeRun\\Runs\\OrdersWereClosed') {
                return false;
            }
        }

        return true;
    }

    private function assertOrdersAreOpen()
    {
        if ($this->ordersCanBeMade() === false) {
            throw new OrdersAlreadyClosed();
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getRecordedEvents()
    {
        return $this->recordedEvents;
    }
}
