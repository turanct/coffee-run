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

        $coffeeRun = new static($id, $events);
        $coffeeRun->recordedEvents = $events;

        return $coffeeRun;
    }

    public function orderProduct(
        ProductId $productId,
        UserId $userId,
        Clock $clock
    ) {
        $this->assertOrdersAreOpen();

        $event = new ProductWasOrdered(
            $this->id,
            $userId,
            $productId,
            $clock->getTime()
        );

        $this->events[] = $event;
        $this->recordedEvents[] = $event;
    }

    public function stopOrdering(Clock $clock)
    {
        $this->assertOrdersAreOpen();

        $event = new OrdersWereClosed($this->id, $clock->getTime());

        $this->events[] = $event;
        $this->recordedEvents[] = $event;
    }

    private function assertOrdersAreOpen()
    {
        foreach ($this->events as $event) {
            if (get_class($event) === 'CoffeeRun\\OrdersWereClosed') {
                throw new OrdersAlreadyClosed();
            }
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
