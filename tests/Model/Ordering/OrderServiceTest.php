<?php

namespace App\Tests\Model\Ordering;

use App\Domain\Model\Order;
use App\Domain\Model\OrderId;
use App\Domain\OrderRepositoryInterface;
use App\Domain\Service\OrderService;
use App\Infrastructure\Service\HereMapDistance;
use App\Tests\TestCase;

class BookingServiceTest extends TestCase
{
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var OrderService
     */
    private $orderService;

    protected function setUp(): void
    {
        $this->createEntitySchema(Order::class);

        $this->orderRepository = $this->getTestEntityManager()->getRepository('App\Domain\Model\Order');

        $this->orderService = new OrderService(
            $this->orderRepository,
            new HereMapDistance()
        );
    }

    /** @test */
    public function it_creates_a_new_order_and_assign_route(): void
    {
        $origin =  ['35.714566', '51.367992'];
        $destination = ['35.719253', '51.363357'];
        $data = $this->orderService->addNewOrder($origin, $destination);

        $this->assertNotEmpty($data);

        $order = $this->orderRepository->get(OrderId::fromString($data['id']));

        $this->assertNotNull($order);

        $this->assertEquals($data['status'], $order->status()->currentStatus());
        $this->assertEquals($data['distance'], $order->distance());
    }

    /** @test */
    public function it_lists_all_stored_orders(): void
    {
        $origin =  ['35.714566', '51.367992'];
        $destination = ['35.719253', '51.363357'];
        $data = $this->orderService->addNewOrder($origin, $destination);
        $ordersId[] = $this->orderRepository->get(OrderId::fromString($data['id']))->id();

        $origin =  ['35.514566', '51.467992'];
        $destination = ['35.419253', '51.163357'];
        $data = $this->orderService->addNewOrder($origin, $destination);
        $ordersId[] = $this->orderRepository->get(OrderId::fromString($data['id']))->id();

        $allData = $this->orderService->allOrders(1,5);

        /** @var Order $order */
        foreach ($allData as $order) {
            $this->assertTrue(in_array($order['id'], $ordersId));
        }
    }
}
 