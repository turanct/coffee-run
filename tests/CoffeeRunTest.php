<?php

namespace CoffeeRun;

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

        $product1 = ProductId::generate();
        $userId = UserId::generate();
        $coffeeRun->orderProduct($product1, $userId);

        $product2 = ProductId::generate();
        $otherUser = UserId::generate();
        $coffeeRun->orderProduct($product2, $otherUser);

        $this->assertEquals(
            array(
                new ProductWasOrdered($id, $userId, $product1, $now),
                new ProductWasOrdered($id, $otherUser, $product2, $now),
            ),
            $coffeeRun->getRecordedEvents()
        );
    }

    /**
     * @expectedException CoffeeRun\OrdersAlreadyClosed
     */
    public function test_it_rejects_orders_when_orders_were_closed()
    {
        $clock = $this->getClock(new DateTime('now'));
        $coffeeRun = $this->freshCoffeeRun($clock);

        $coffeeRun->stopOrdering($clock);

        $product1 = ProductId::generate();
        $userId = UserId::generate();
        $coffeeRun->orderProduct($product1, $userId, $clock);
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
