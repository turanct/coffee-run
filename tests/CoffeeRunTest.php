<?php

namespace CoffeeRun\Runs;

use CoffeeRun\UserId;
use CoffeeRun\Clock;
use DateTime;

class CoffeeRunTest extends \PHPUnit_Framework_TestCase
{
    public function test_it_can_be_announced()
    {
        $userId = UserId::generate();
        $shopId = ShopId::generate();
        $time = new DateTime('tomorrow');
        $now = new DateTime('now');
        $clock = $this->getClock($now);

        $coffeeRun = CoffeeRun::announce($userId, $shopId, $time, $clock);

        $this->assertEquals(
            array(
                new CoffeeRunWasAnnounced(
                    $coffeeRun->getId(),
                    $userId,
                    $shopId,
                    $time,
                    $now
                ),
            ),
            $coffeeRun->getRecordedEvents()
        );
    }

    public function test_it_accepts_orders()
    {
        $now = new DateTime('now');
        $clock = $this->getClock($now);
        $coffeeRun = $this->freshCoffeeRun($clock);
        $id = $coffeeRun->getId();

        $order1 = $this->freshOrder(200, 'foo');
        $coffeeRun->placeOrder($order1);

        $order2 = $this->freshOrder(400, 'bar');
        $coffeeRun->placeOrder($order2);

        $this->assertEquals(
            array(
                new OrderWasPlaced($id, $order1, $now),
                new OrderWasPlaced($id, $order2, $now),
            ),
            $coffeeRun->getRecordedEvents()
        );
    }

    /**
     * @expectedException CoffeeRun\Runs\OrdersAlreadyClosed
     */
    public function test_it_rejects_orders_when_orders_were_closed()
    {
        $clock = $this->getClock(new DateTime('now'));
        $coffeeRun = $this->freshCoffeeRun($clock);

        $coffeeRun->stopOrdering($clock);

        $order = $this->freshOrder(200, 'foo');
        $coffeeRun->placeOrder($order);
    }

    private function freshCoffeeRun(Clock $clock)
    {
        $id = CoffeeRunId::generate();
        $userId = UserId::generate();
        $shopId = ShopId::generate();
        $time = new DateTime('now +1 hour');
        $now = new DateTime('now');

        $events = array(
            new CoffeeRunWasAnnounced($id, $userId, $shopId, $time, $now),
        );

        $coffeeRun = new CoffeeRun($id, $events, $clock);

        return $coffeeRun;
    }

    private function freshOrder($price, $description)
    {
        $order = new Order(
            UserId::generate(),
            ProductId::generate(),
            new Price($price),
            new Description($description)
        );

        return $order;
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
