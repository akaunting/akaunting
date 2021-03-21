<?php

namespace Doctrine\DBAL\Driver;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Driver\AbstractOracleDriver\EasyConnectString;
use Doctrine\DBAL\Driver\DriverException as DeprecatedDriverException;
use Doctrine\DBAL\Exception\ConnectionException;
use Doctrine\DBAL\Exception\DriverException;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\DBAL\Exception\InvalidFieldNameException;
use Doctrine\DBAL\Exception\NonUniqueFieldNameException;
use Doctrine\DBAL\Exception\NotNullConstraintViolationException;
use Doctrine\DBAL\Exception\SyntaxErrorException;
use Doctrine\DBAL\Exception\TableExistsException;
use Doctrine\DBAL\Exception\TableNotFoundException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\DBAL\Platforms\OraclePlatform;
use Doctrine\DBAL\Schema\OracleSchemaManager;

/**
 * Abstract base implementation of the {@link Driver} interface for Oracle based drivers.
 */
abstract class AbstractOracleDriver implements Driver, ExceptionConverterDriver
{
    /**
     * {@inheritdoc}
     *
     * @deprecated
     */
    public function convertException($message, DeprecatedDriverException $exception)
    {
        switch ($exception->getErrorCode()) {
            case '1':
            case '2299':
            case '38911':
                return new UniqueConstraintViolationException($message, $exception);

            case '904':
                return new InvalidFieldNameException($message, $exception);

            case '918':
            case '960':
                return new NonUniqueFieldNameException($message, $exception);

            case '923':
                return new SyntaxErrorException($message, $exception);

            case '942':
                return new TableNotFoundException($message, $exception);

            case '955':
                return new TableExistsException($message, $exception);

            case '1017':
            case '12545':
                return new ConnectionException($message, $exception);

            case '1400':
                return new NotNullConstraintViolationException($message, $exception);

            case '2266':
            case '2291':
            case '2292':
                return new ForeignKeyConstraintViolationException($message, $exception);
        }

        return new DriverException($message, $exception);
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated Use Connection::getDatabase() instead.
     */
    public function getDatabase(Connection $conn)
    {
        $params = $conn->getParams();

        return $params['user'];
    }

    /**
     * {@inheritdoc}
     */
    public function getDatabasePlatform()
    {
        return new OraclePlatform();
    }

    /**
     * {@inheritdoc}
     */
    public function getSchemaManager(Connection $conn)
    {
        return new OracleSchemaManager($conn);
    }

    /**
     * Returns an appropriate Easy Connect String for the given parameters.
     *
     * @param mixed[] $params The connection parameters to return the Easy Connect String for.
     *
     * @return string
     */
    protected function getEasyConnectString(array $params)
    {
        return (string) EasyConnectString::fromConnectionParameters($params);
    }
}
