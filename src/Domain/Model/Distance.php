<?php

declare(strict_types = 1);

namespace App\Domain\Model;

use App\Domain\DistanceCalculatorInterface;

class Distance
{
    /**
     * @var Route
     */
    private $route;

    /**
     * @var DistanceCalculatorInterface
     */
    private $distanceCalculator;

    /**
     * @var integer
     */
    private $distance;

    /**
     * Distance constructor.
     *
     * @param Route                       $route
     * @param DistanceCalculatorInterface $distanceCalculator
     */
    public function __construct(Route $route, DistanceCalculatorInterface $distanceCalculator)
    {
        $this->route = $route;
        $this->distanceCalculator = $distanceCalculator;
        $this->distance =  $this->distanceCalculator->calculate($route);
    }

    /**
     * I prefer delegation instead of inheritance
     * @return Location
     */
    public function origin():Location
    {
        return $this->route->origin();
    }

    /**
     * I prefer delegation instead of inheritance
     * @return Location
     */
    public function destination():Location
    {
        return $this->route->destination();
    }

    /**
     * @return int
     */
    public function spacing():int
    {
        return $this->distance;
    }

    /**
     * @param Distance $other
     *
     * @return bool
     */
    public function sameAsValue(Distance $other):bool
    {
        return $this->spacing() === $other->spacing();
    }

}