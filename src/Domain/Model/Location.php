<?php

declare(strict_types = 1);

namespace App\Domain\Model;

use Assert\Assertion;

class Location
{
    /**
     * @var float
     */
    private $latitude;

    /**
     * @var float
     */
    private $longitude;

    /**
     * @param array $point
     *
     * @return Location
     */
    static function thatArray(array $point)
    {
        Assertion::notEmpty($point, 'point is empty');
        Assertion::keyIsset($point, 1, 'wrong point, longitude is missing');
        return new self((float)$point[0], (float)$point[1]);
    }

    /**
     * Location constructor.
     *
     * @param float $latitude
     * @param float $longitude
     */
    public function __construct(float $latitude, float $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $errorMessage = 'sorry, wrong point, please check lat:' . $latitude . ' and long: ' . $longitude;
        Assertion::regex((string)$this->latitude, '/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/', $errorMessage);
        Assertion::regex((string)$this->longitude, '/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/', $errorMessage);

    }

    /**
     * @return float
     */
    public function latitude()
    {
        return $this->latitude;
    }

    /**
     * @return float
     */
    public function longitude()
    {
        return $this->longitude;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->latitude . ',' . $this->longitude;
    }

    /**
     * @param Location $location
     *
     * @return bool
     */
    public function sameValueAs(Location $location): bool
    {
        if ((string)$location == (string)$this) {
            return true;
        }
        return false;
    }
}