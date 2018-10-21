<?php

declare(strict_types = 1);

namespace App\Domain;

use App\Domain\Model\Order;
use App\Domain\Model\OrderId;

interface OrderRepositoryInterface
{
    /**
     * Finds a order using given id.
     *
     * @param OrderId $trackingId Id
     *
     * @return Order if found, else {@code null}
     */
    public function get(OrderId $trackingId): ?Order;

    /**
     * Saves given order.
     *
     * @param Order $order order to save
     */
    public function store(Order $order): void;

    /**
     * @return OrderId A unique, generated tracking Id.
     */
    public function getNextTrackingId(): OrderId;
}