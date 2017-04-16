<?php

namespace CoffeeRun\Runs;

use CoffeeRun\UserId;
use DateTime;

class CoffeeRunTest extends \PHPUnit_Framework_TestCase
{
    public function test_it_can_be_announced()
    {
        $id = CoffeeRunId::generate();
        $userId = UserId::generate();
        $shopId = ShopId::generate();
        $time = new DateTime('tomorrow');
        $now = new DateTime('now');

        $coffeeRun = CoffeeRun::announce($id, $userId, $shopId, $time, $now);

        $this->assertEquals(
            array(
                new CoffeeRunWasAnnounced(
                    $id,
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
        $coffeeRun = $this->freshCoffeeRun();
        $id = $coffeeRun->getId();

        $order1 = $this->freshOrder(200, 'foo');
        $coffeeRun->placeOrder($order1, $now);

        $order2 = $this->freshOrder(400, 'bar');
        $coffeeRun->placeOrder($order2, $now);

        $this->assertTrue($coffeeRun->ordersCanBeMade());

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
        $now = new DateTime('now');

        $coffeeRun = $this->freshCoffeeRun();
        $coffeeRun->stopOrdering($now);

        $this->assertFalse($coffeeRun->ordersCanBeMade());

        $order = $this->freshOrder(200, 'foo');
        $coffeeRun->placeOrder($order, $now);
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
}
