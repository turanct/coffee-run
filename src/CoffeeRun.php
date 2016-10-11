<?php

namespace CoffeeRun;

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

    public static function announce(UserId $userId, ShopId $shopId, DateTime $time)
    {
        $id = CoffeeRunId::generate();

        $events = array();
        $events[] = new CoffeeRunWasAnnounced($id, $userId, $shopId, $time);

        $coffeeRun = new static($id, $events);
        $coffeeRun->recordedEvents = $events;

        return $coffeeRun;
    }

    public function orderProduct(ProductId $productId, UserId $userId)
    {
        $this->assertOrdersAreOpen();

        $event = new ProductWasOrdered($this->id, $userId, $productId);

        $this->events[] = $event;
        $this->recordedEvents[] = $event;
    }

    public function stopOrdering()
    {
        $this->assertOrdersAreOpen();

        $event = new OrdersWereClosed($this->id);

        $this->events[] = $event;
        $this->recordedEvents[] = $event;
    }

    private function assertOrdersAreOpen()
    {
        $closedEvent = new OrdersWereClosed($this->id);

        if (in_array($closedEvent, $this->events)) {
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
