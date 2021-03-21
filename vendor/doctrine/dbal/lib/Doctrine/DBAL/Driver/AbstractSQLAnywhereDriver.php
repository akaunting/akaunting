<?php

namespace Doctrine\DBAL\Driver;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Driver\DriverException as DeprecatedDriverException;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Exception\ConnectionException;
use Doctrine\DBAL\Exception\DeadlockException;
use Doctrine\DBAL\Exception\DriverException;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\DBAL\Exception\InvalidFieldNameException;
use Doctrine\DBAL\Exception\LockWaitTimeoutException;
use Doctrine\DBAL\Exception\NonUniqueFieldNameException;
use Doctrine\DBAL\Exception\NotNullConstraintViolationException;
use Doctrine\DBAL\Exception\SyntaxErrorException;
use Doctrine\DBAL\Exception\TableExistsException;
use Doctrine\DBAL\Exception\TableNotFoundException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\DBAL\Platforms\SQLAnywhere11Platform;
use Doctrine\DBAL\Platforms\SQLAnywhere12Platform;
use Doctrine\DBAL\Platforms\SQLAnywhere16Platform;
use Doctrine\DBAL\Platforms\SQLAnywherePlatform;
use Doctrine\DBAL\Schema\SQLAnywhereSchemaManager;
use Doctrine\DBAL\VersionAwarePlatformDriver;

use function assert;
use function preg_match;
use function version_compare;

/**
 * Abstract base implementation of the {@link Driver} interface for SAP Sybase SQL Anywhere based drivers.
 */
abstract class AbstractSQLAnywhereDriver implements Driver, ExceptionConverterDriver, VersionAwarePlatformDriver
{
    /**
     * {@inheritdoc}
     *
     * @deprecated
     *
     * @link http://dcx.sybase.com/index.html#sa160/en/saerrors/sqlerror.html
     */
    public function convertException($message, DeprecatedDriverException $exception)
    {
        switch ($exception->getErrorCode()) {
            case '-306':
            case '-307':
            case '-684':
                return new DeadlockException($message, $exception);

            case '-210':
            case '-1175':
            case '-1281':
                return new LockWaitTimeoutException($message, $exception);

            case '-100':
            case '-103':
            case '-832':
                return new ConnectionException($message, $exception);

            case '-143':
                return new InvalidFieldNameException($message, $exception);

            case '-193':
            case '-196':
                return new UniqueConstraintViolationException($message, $exception);

            case '-194':
            case '-198':
                return new ForeignKeyConstraintViolationException($message, $exception);

            case '-144':
                return new NonUniqueFieldNameException($message, $exception);

            case '-184':
            case '-195':
                return new NotNullConstraintViolationException($message, $exception);

            case '-131':
                return new SyntaxErrorException($message, $exception);

            case '-110':
                return new TableExistsException($message, $exception);

            case '-141':
            case '-1041':
                return new TableNotFoundException($message, $exception);
        }

        return new DriverException($message, $exception);
    }

    /**
     * {@inheritdoc}
     */
    public function createDatabasePlatformForVersion($version)
    {
        if (
            ! preg_match(
                '/^(?P<major>\d+)(?:\.(?P<minor>\d+)(?:\.(?P<patch>\d+)(?:\.(?P<build>\d+))?)?)?/',
                $version,
                $versionParts
            )
        ) {
            throw Exception::invalidPlatformVersionSpecified(
                $version,
                '<major_version>.<minor_version>.<patch_version>.<build_version>'
            );
        }

        $majorVersion = $versionParts['major'];
        $minorVersion = $versionParts['minor'] ?? 0;
        $patchVersion = $versionParts['patch'] ?? 0;
        $buildVersion = $versionParts['build'] ?? 0;
        $version      = $majorVersion . '.' . $minorVersion . '.' . $patchVersion . '.' . $buildVersion;

        switch (true) {
            case version_compare($version, '16', '>='):
                return new SQLAnywhere16Platform();

            case version_compare($version, '12', '>='):
                return new SQLAnywhere12Platform();

            case version_compare($version, '11', '>='):
                return new SQLAnywhere11Platform();

            default:
                return new SQLAnywherePlatform();
        }
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated Use Connection::getDatabase() instead.
     */
    public function getDatabase(Connection $conn)
    {
        $params = $conn->getParams();

        if (isset($params['dbname'])) {
            return $params['dbname'];
        }

        $database = $conn->query('SELECT DB_NAME()')->fetchColumn();

        assert($database !== false);

        return $database;
    }

    /**
     * {@inheritdoc}
     */
    public function getDatabasePlatform()
    {
        return new SQLAnywhere12Platform();
    }

    /**
     * {@inheritdoc}
     */
    public function getSchemaManager(Connection $conn)
    {
        return new SQLAnywhereSchemaManager($conn);
    }
}
