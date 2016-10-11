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

        $coffeeRun = CoffeeRun::announce($userId, $shopId, $time);

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
        $coffeeRun = $this->freshCoffeeRun();
        $id = $coffeeRun->getId();

        $now = new DateTime('now');

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
        $coffeeRun = $this->freshCoffeeRun();

        $coffeeRun->stopOrdering();

        $product1 = ProductId::generate();
        $userId = UserId::generate();
        $coffeeRun->orderProduct($product1, $userId);
    }

    private function freshCoffeeRun()
    {
        $id = CoffeeRunId::generate();
        $userId = UserId::generate();
        $shopId = ShopId::generate();
        $time = new DateTime('now +1 hour');
        $now = new DateTime('now');

        $events = array(
            new CoffeeRunWasAnnounced($id, $userId, $shopId, $time, $now),
        );

        $coffeeRun = new CoffeeRun($id, $events);

        return $coffeeRun;
    }
}
