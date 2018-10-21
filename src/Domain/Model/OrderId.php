<?php

declare(strict_types = 1);

namespace App\Domain\Model;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class OrderId
{
    /**
     * @param string $aTrackingId
     *
     * @return OrderId
     */
    public static function fromString(string $aTrackingId): OrderId
    {
        return new self(Uuid::fromString($aTrackingId));
    }

    /**
     * @return OrderId
     * @throws \Exception
     */
    public static function generate(): OrderId
    {
        return new self(Uuid::uuid4());
    }

    /**
     * @var Uuid
     */
    private $uuid;

    /**
     * Always provide a string representation of the TrackingId
     * 
     * @param UuidInterface $aUuid
     * 
     * @throws InvalidArgumentException
     */
    public function __construct(UuidInterface $aUuid)
    {
        $this->uuid = $aUuid;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->uuid->toString();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * @param OrderId $other
     *
     * @return bool
     */
    public function sameValueAs(OrderId $other): bool
    {
        return $this->toString() === $other->toString();
    }
}
