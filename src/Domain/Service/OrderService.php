<?php

namespace App\Domain\Service;

use App\Domain\DistanceCalculatorInterface;
use App\Domain\Model\Distance;
use App\Domain\Model\Location;
use App\Domain\Model\Order;
use App\Domain\Model\OrderStatus;
use App\Domain\Model\Route;
use App\Domain\Model\OrderId;
use App\Domain\OrderRepositoryInterface;
use App\Infrastructure\Dto\OrdersDto;
use Assert\Assertion;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Response;

class OrderService
{
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var DistanceCalculatorInterface
     */
    private $distanceCalculator;

    /**
     * OrderService constructor.
     *
     * @param OrderRepositoryInterface    $orderRepository
     * @param DistanceCalculatorInterface $distanceCalculator
     */
    public function __construct(OrderRepositoryInterface $orderRepository, DistanceCalculatorInterface $distanceCalculator)
    {
        $this->orderRepository = $orderRepository;
        $this->distanceCalculator = $distanceCalculator;
    }

    /**
     * @param array $origin
     * @param array $destination
     *
     * @return array
     */
    public function addNewOrder($origin, $destination): array
    {
        $orderId = $this->orderRepository->getNextOrderId();

        $route = new Route(Location::thatArray($origin), Location::thatArray($destination));
        $distance = new Distance($route, $this->distanceCalculator);
        $order = new Order($route, $orderId, $distance);

        $this->orderRepository->store($order);

        return [
            'id' => $orderId->toString(),
            'distance' => $distance->spacing(),
            'status' => $order->status()->currentStatus()
        ];
    }

    /**
     * @param string $orderId
     * @param string $status
     */
    public function takeOrder(string $orderId, string $status): void
    {
        $orderId = OrderId::fromString($orderId);
        $order = $this->orderRepository->get($orderId);
        Assertion::notEmpty(
            $order,
            'order with ' . $orderId . ' is not exist',
            Response::HTTP_NOT_FOUND
        );
        $order->changeStatus(new OrderStatus($status));

        $this->orderRepository->store($order);
    }

    /**
     * @param int $page
     * @param int $limit
     *
     * @return array
     */
    public function allOrders(int $page, int $limit): array
    {
        /** @var Pagerfanta $ordersData */
        $ordersData = $this->orderRepository->getAll($page, $limit);
        return (new OrdersDto($ordersData->getIterator()))->getArrayCopy();
    }

}