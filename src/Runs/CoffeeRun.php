<?php

namespace CoffeeRun\Runs;

use CoffeeRun\UserId;
use CoffeeRun\Clock;
use DateTime;

final class CoffeeRun
{
    private $id;
    private $events;
    private $recordedEvents = array();
    private $clock;

    public function __construct(CoffeeRunId $id, array $events, Clock $clock)
    {
        $this->id = $id;
        $this->events = $events;
        $this->clock = $clock;
    }

    public static function announce(
        UserId $userId,
        ShopId $shopId,
        DateTime $time,
        Clock $clock
    ) {
        $id = CoffeeRunId::generate();

        $events = array();
        $events[] = new CoffeeRunWasAnnounced(
            $id,
            $userId,
            $shopId,
            $time,
            $clock->getTime()
        );

        $coffeeRun = new static($id, $events, $clock);
        $coffeeRun->recordedEvents = $events;

        return $coffeeRun;
    }

    public function placeOrder(Order $order)
    {
        $this->assertOrdersAreOpen();

        $event = new OrderWasPlaced(
            $this->id,
            $order,
            $this->clock->getTime()
        );

        $this->events[] = $event;
        $this->recordedEvents[] = $event;
    }

    public function stopOrdering()
    {
        $this->assertOrdersAreOpen();

        $event = new OrdersWereClosed($this->id, $this->clock->getTime());

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
