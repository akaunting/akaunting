<?php

namespace Doctrine\DBAL\Types;

use DateTime;
use DateTimeInterface;
use Doctrine\DBAL\Platforms\AbstractPlatform;

use function date_create;

/**
 * Variable DateTime Type using date_create() instead of DateTime::createFromFormat().
 *
 * This type has performance implications as it runs twice as long as the regular
 * {@see DateTimeType}, however in certain PostgreSQL configurations with
 * TIMESTAMP(n) columns where n > 0 it is necessary to use this type.
 */
class VarDateTimeType extends DateTimeType
{
    /**
     * {@inheritDoc}
     *
     * @param T $value
     *
     * @return (T is null ? null : DateTimeInterface)
     *
     * @template T
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value instanceof DateTime) {
            return $value;
        }

        $val = date_create($value);
        if ($val === false) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }

        return $val;
    }
}
