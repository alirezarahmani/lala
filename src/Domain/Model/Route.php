<?php

declare(strict_types = 1);

namespace App\Domain\Model;

use Assert\Assertion;

class Route
{
    /**
     * Origin Location
     *
     * @var Location
     */
    private $origin;

    /**
     * Destination Location
     *
     * @var Location
     */
    private $destination;

    /**
     * @param Location $origin
     * @param Location $destination
     */
    public function __construct(Location $origin, Location $destination)
    {
        Assertion::false(
            ((string)$origin === (string)$destination),
            'It seems your route is wrong, origin and destination are the same'
        );
        $this->origin = $origin;
        $this->destination = $destination;
    }

    /**
     * @return Location
     */
    public function origin(): Location
    {
        return $this->origin;
    }

    /**
     * @return Location
     */
    public function destination(): Location
    {
        return $this->destination;
    }

    /**
     * @param Route $other
     * @return bool
     */
    public function sameValueAs(Route $other): bool
    {
        if ((string)$this->origin() !== (string)$other->origin()) {
            return false;
        }

        if ((string)$this->destination() !== (string)$other->destination()) {
            return false;
        }

        return true;
    }
}