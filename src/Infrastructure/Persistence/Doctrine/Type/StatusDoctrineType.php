<?php

declare(strict_types = 1);

namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\Model\OrderStatus;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\TextType;

final class StatusDoctrineType extends TextType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'status_doctrine_type';
    }

    /**
     * @param                  $value
     * @param AbstractPlatform $platform
     *
     * @return OrderStatus
     * @throws ConversionException
     */
    public function convertToPhpValue($value, AbstractPlatform $platform): OrderStatus
    {
        if (null === $value) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }

        return new OrderStatus($value);
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
        /** @var OrderStatus $value */
        if (!is_a($value, OrderStatus::class)) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }

        return $value->currentStatus();
    }
} 