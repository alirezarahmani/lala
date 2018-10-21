<?php

declare(strict_types = 1);

namespace App\Infrastructure\Dto;

use App\Domain\Model\Order;

class OrdersDto
{
    /**
     * @var \ArrayIterator
     */
    private $orders;

    public function __construct(\ArrayIterator $orders)
    {
        $this->orders = $orders;
    }

    public function getArrayCopy(): array
    {
        $orders = $this->orders->getArrayCopy();
        $data = [];

        /** @var Order $order */
        foreach ($orders as $order) {
            $data[] = [
                'id' => $order->id()->toString(),
                'distance' => $order->distance(),
                'status' => $order->status()->currentStatus()
            ];
        }

        return $data;
    }
}