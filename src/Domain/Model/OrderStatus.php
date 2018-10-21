<?php

declare(strict_types = 1);

namespace App\Domain\Model;

use Assert\Assertion;

class OrderStatus
{
    public const STATUES = [self::UNASSIGN, self::TAKEN];
    public const UNASSIGN = 'UNASSIGN';
    public const TAKEN = 'TAKEN';

    /**
     * @var string
     */
    private $status;

    /**
     * OrderStatus constructor.
     *
     * @param string $status
     */
    public function __construct(string $status)
    {
        //skip capital or non capital
        $status = strtoupper($status);
        Assertion::inArray($status, self::STATUES, $status . ': is a Wrong order status');
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function currentStatus(): string
    {
        return $this->status;
    }

    /**
     * @param OrderStatus $other
     *
     * @return bool
     */
    public function sameAsValue(OrderStatus $other): bool
    {
        return $this->status === $other->currentStatus();
    }
}