<?php

declare(strict_types = 1);

namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\Model\Location;
use App\Domain\Model\Route;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\TextType;

final class RouteDoctrineType extends TextType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'route_doctrine_type';
    }

    /**
     * @param                  $value
     * @param AbstractPlatform $platform
     *
     * @return Route
     * @throws ConversionException
     */
    public function convertToPhpValue($value, AbstractPlatform $platform): Route
    {
        if (null === $value) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }

        $route = json_decode($value, true);
        $origin = new Location($route['originLat'], $route['originLon']);
        $destination = new Location($route['destinationLat'], $route['destinationLon']);

        return new Route($origin, $destination);
    }

    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     *
     * @return null|string
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }
        /** @var Route $value */
        if (!is_a($value, Route::class)) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }

        $routeData = [
            'originLat' => $value->origin()->latitude(),
            'originLon' => $value->origin()->longitude(),
            'destinationLat' => $value->destination()->latitude(),
            'destinationLon' => $value->destination()->longitude(),
        ];

        return json_encode($routeData);
    }
} 