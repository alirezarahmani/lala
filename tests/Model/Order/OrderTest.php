<?php

namespace App\Tests\Model\Order;

use App\Domain\Model\Distance;
use App\Domain\Model\Location;
use App\Domain\Model\Order;
use App\Domain\Model\OrderId;
use App\Domain\Model\OrderStatus;
use App\Domain\Model\Route;
use App\Infrastructure\Service\HereMapDistance;
use App\Tests\TestCase;
use Ramsey\Uuid\Uuid;

class OrderTest extends TestCase
{
    /** @var Route */
    private $route;

    /** @var Uuid */
    private $uuid;

    /** @var Distance */
    private $distance;

    /** @var Location */
    private $origin;

    /** @var Location */
    private $destination;

    /**
     * setup defaults
     */
    public function setUp()
    {
        parent::setUp();
        $distanceCalculator = \Mockery::mock(HereMapDistance::class);
        $distanceCalculator->shouldReceive('calculate')->andReturn(1078);
        $this->route = new Route($this->origin = new Location(35.714566, 51.367992), $this->destination = new Location(35.719253, 51.363357));
        $this->distance = new Distance($this->route, $distanceCalculator);
        $this->uuid = Uuid::uuid4();
    }

    /** @test */
    public function it_returns_its_order_id(): void
    {
        $order = new Order($this->route, new OrderId($this->uuid), $this->distance);
        $orderId = new OrderId($this->uuid);
        $this->assertTrue($orderId->sameValueAs($order->id()));
    }

    /** @test */
    public function it_returns_initial_route(): void
    {
        $order = new Order($this->route, new OrderId($this->uuid), $this->distance);

        $this->assertEquals($this->origin, $order->route()->origin());
        $this->assertEquals($this->destination, $order->route()->destination());
    }

    /** @test */
    public function it_return_status_of_order():void
    {
        $order = new Order($this->route, new OrderId($this->uuid), $this->distance);
        $this->assertEquals(OrderStatus::UNASSIGN, $order->status()->currentStatus());
    }

    /** @test */
    public function it_specifies_new_status(): void
    {
        $order = new Order($this->route, new OrderId($this->uuid), $this->distance);
        $newStatus = new OrderStatus(OrderStatus::TAKEN);

        $order->changeStatus($newStatus);
        $this->assertTrue($newStatus->sameAsValue($order->status()));
    }
}