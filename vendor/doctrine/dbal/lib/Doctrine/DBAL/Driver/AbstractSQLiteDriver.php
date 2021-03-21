<?php

namespace Doctrine\DBAL\Driver;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Driver\DriverException as DeprecatedDriverException;
use Doctrine\DBAL\Exception\ConnectionException;
use Doctrine\DBAL\Exception\DriverException;
use Doctrine\DBAL\Exception\InvalidFieldNameException;
use Doctrine\DBAL\Exception\LockWaitTimeoutException;
use Doctrine\DBAL\Exception\NonUniqueFieldNameException;
use Doctrine\DBAL\Exception\NotNullConstraintViolationException;
use Doctrine\DBAL\Exception\ReadOnlyException;
use Doctrine\DBAL\Exception\SyntaxErrorException;
use Doctrine\DBAL\Exception\TableExistsException;
use Doctrine\DBAL\Exception\TableNotFoundException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\DBAL\Platforms\SqlitePlatform;
use Doctrine\DBAL\Schema\SqliteSchemaManager;

use function strpos;

/**
 * Abstract base implementation of the {@link Driver} interface for SQLite based drivers.
 */
abstract class AbstractSQLiteDriver implements Driver, ExceptionConverterDriver
{
    /**
     * {@inheritdoc}
     *
     * @deprecated
     *
     * @link http://www.sqlite.org/c3ref/c_abort.html
     */
    public function convertException($message, DeprecatedDriverException $exception)
    {
        if (strpos($exception->getMessage(), 'database is locked') !== false) {
            return new LockWaitTimeoutException($message, $exception);
        }

        if (
            strpos($exception->getMessage(), 'must be unique') !== false ||
            strpos($exception->getMessage(), 'is not unique') !== false ||
            strpos($exception->getMessage(), 'are not unique') !== false ||
            strpos($exception->getMessage(), 'UNIQUE constraint failed') !== false
        ) {
            return new UniqueConstraintViolationException($message, $exception);
        }

        if (
            strpos($exception->getMessage(), 'may not be NULL') !== false ||
            strpos($exception->getMessage(), 'NOT NULL constraint failed') !== false
        ) {
            return new NotNullConstraintViolationException($message, $exception);
        }

        if (strpos($exception->getMessage(), 'no such table:') !== false) {
            return new TableNotFoundException($message, $exception);
        }

        if (strpos($exception->getMessage(), 'already exists') !== false) {
            return new TableExistsException($message, $exception);
        }

        if (strpos($exception->getMessage(), 'has no column named') !== false) {
            return new InvalidFieldNameException($message, $exception);
        }

        if (strpos($exception->getMessage(), 'ambiguous column name') !== false) {
            return new NonUniqueFieldNameException($message, $exception);
        }

        if (strpos($exception->getMessage(), 'syntax error') !== false) {
            return new SyntaxErrorException($message, $exception);
        }

        if (strpos($exception->getMessage(), 'attempt to write a readonly database') !== false) {
            return new ReadOnlyException($message, $exception);
        }

        if (strpos($exception->getMessage(), 'unable to open database file') !== false) {
            return new ConnectionException($message, $exception);
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

        return $params['path'] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function getDatabasePlatform()
    {
        return new SqlitePlatform();
    }

    /**
     * {@inheritdoc}
     */
    public function getSchemaManager(Connection $conn)
    {
        return new SqliteSchemaManager($conn);
    }
}
