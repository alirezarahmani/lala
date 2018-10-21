<?php

declare(strict_types = 1);

namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\Model\OrderId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\TextType;

final class OrderIdDoctrineType extends TextType
{
    const NAME = 'order_id';

    /**
     * {@inheritdoc}
     *
     * @param                  $value
     * @param AbstractPlatform $platform
     *
     * @return OrderId|null
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?OrderId
    {
        if (empty($value)) {
            return null;
        }

        if ($value instanceof OrderId) {
            return $value;
        }

        try {
            return OrderId::fromString($value);
        } catch (\Exception $ex) {
            throw ConversionException::conversionFailed($value, self::NAME);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param mixed            $value
     * @param AbstractPlatform $platform
     *
     * @return null|string
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if ($value instanceof OrderId) {
            return $value->toString();
        }

        throw ConversionException::conversionFailed($value, self::NAME);
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return self::NAME;
    }
}
