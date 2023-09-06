<?php

namespace Doctrine\DBAL\Driver;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Driver\API\ExceptionConverter as ExceptionConverterInterface;
use Doctrine\DBAL\Driver\API\IBMDB2\ExceptionConverter;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\DB2Platform;
use Doctrine\DBAL\Schema\DB2SchemaManager;
use Doctrine\Deprecations\Deprecation;

use function assert;

/**
 * Abstract base implementation of the {@see Driver} interface for IBM DB2 based drivers.
 */
abstract class AbstractDB2Driver implements Driver
{
    /**
     * {@inheritDoc}
     */
    public function getDatabasePlatform()
    {
        return new DB2Platform();
    }

    /**
     * {@inheritDoc}
     *
     * @deprecated Use {@link DB2Platform::createSchemaManager()} instead.
     */
    public function getSchemaManager(Connection $conn, AbstractPlatform $platform)
    {
        Deprecation::triggerIfCalledFromOutside(
            'doctrine/dbal',
            'https://github.com/doctrine/dbal/pull/5458',
            'AbstractDB2Driver::getSchemaManager() is deprecated.'
                . ' Use DB2Platform::createSchemaManager() instead.',
        );

        assert($platform instanceof DB2Platform);

        return new DB2SchemaManager($conn, $platform);
    }

    public function getExceptionConverter(): ExceptionConverterInterface
    {
        return new ExceptionConverter();
    }
}
