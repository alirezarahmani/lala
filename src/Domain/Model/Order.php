<?php

declare(strict_types = 1);

namespace App\Domain\Model;

use Assert\Assertion;
use Symfony\Component\HttpFoundation\Response;

class Order
{
    /**
     * @var OrderId
     */
    private $id;

    /**
     * @var Route
     */
    private $route;

    /**
     * @var integer
     */
    private $distance;

    /**
     * @var OrderStatus
     */
    private $status;

    /**
     * Order constructor.
     *
     * @param Route    $route
     * @param OrderId  $orderId
     * @param Distance $distance
     */
    public function __construct(Route $route, OrderId $orderId, Distance $distance)
    {
        $this->route = $route;
        $this->id = $orderId;
        $this->distance = $distance->spacing();

        // according to grasp Information Expert pattern.
        $this->status = new OrderStatus(OrderStatus::UNASSIGN);
    }

    /**
     * @return Route
     */
    public function route(): Route
    {
        return $this->route;
    }

    /**
     * @return OrderStatus
     */
    public function status(): OrderStatus
    {
        return $this->status;
    }

    /**
     * @return OrderId
     */
    public function id(): OrderId
    {
        return $this->id;
    }

    /**
     * @return integer
     */
    public function distance(): int
    {
        return $this->distance;
    }

    /**
     * @param OrderStatus $orderStatus
     */
    public function changeStatus(OrderStatus $orderStatus)
    {
        Assertion::false(
            $orderStatus->sameAsValue($this->status),
            'ORDER_ALREADY_BEEN_TAKEN',
            Response::HTTP_CONFLICT
        );

        $this->status = $orderStatus;
    }

    /**
     * @param Order $other
     *
     * @return bool
     */
    public function sameIdentityAs(Order $other): bool
    {
        return $this->id()->sameValueAs($other->id());
    }

}