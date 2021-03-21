<?php

namespace Doctrine\DBAL\Driver\PDOSqlsrv;

use Doctrine\DBAL\Driver\PDO;
use Doctrine\DBAL\Driver\Result;

/**
 * Sqlsrv Connection implementation.
 *
 * @deprecated Use {@link PDO\SQLSrv\Connection} instead.
 */
class Connection extends PDO\Connection
{
    /**
     * @internal The connection can be only instantiated by its driver.
     *
     * {@inheritdoc}
     */
    public function __construct($dsn, $user = null, $password = null, ?array $options = null)
    {
        parent::__construct($dsn, $user, $password, $options);
        $this->setAttribute(\PDO::ATTR_STATEMENT_CLASS, [PDO\SQLSrv\Statement::class, []]);
    }

    /**
     * {@inheritDoc}
     */
    public function lastInsertId($name = null)
    {
        if ($name === null) {
            return parent::lastInsertId($name);
        }

        $stmt = $this->prepare('SELECT CONVERT(VARCHAR(MAX), current_value) FROM sys.sequences WHERE name = ?');
        $stmt->execute([$name]);

        if ($stmt instanceof Result) {
            return $stmt->fetchOne();
        }

        return $stmt->fetchColumn();
    }
}
