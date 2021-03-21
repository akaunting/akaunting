<?php

namespace Doctrine\DBAL\Driver\SQLSrv;

use Doctrine\DBAL\Driver\Connection as ConnectionInterface;
use Doctrine\DBAL\Driver\Result;
use Doctrine\DBAL\Driver\ServerInfoAwareConnection;
use Doctrine\DBAL\Driver\SQLSrv\Exception\Error;
use Doctrine\DBAL\ParameterType;

use function func_get_args;
use function is_float;
use function is_int;
use function sprintf;
use function sqlsrv_begin_transaction;
use function sqlsrv_commit;
use function sqlsrv_configure;
use function sqlsrv_connect;
use function sqlsrv_errors;
use function sqlsrv_query;
use function sqlsrv_rollback;
use function sqlsrv_rows_affected;
use function sqlsrv_server_info;
use function str_replace;

use const SQLSRV_ERR_ERRORS;

/**
 * SQL Server implementation for the Connection interface.
 *
 * @deprecated Use {@link Connection} instead
 */
class SQLSrvConnection implements ConnectionInterface, ServerInfoAwareConnection
{
    /** @var resource */
    protected $conn;

    /** @var LastInsertId */
    protected $lastInsertId;

    /**
     * @internal The connection can be only instantiated by its driver.
     *
     * @param string  $serverName
     * @param mixed[] $connectionOptions
     *
     * @throws SQLSrvException
     */
    public function __construct($serverName, $connectionOptions)
    {
        if (! sqlsrv_configure('WarningsReturnAsErrors', 0)) {
            throw Error::new();
        }

        $conn = sqlsrv_connect($serverName, $connectionOptions);

        if ($conn === false) {
            throw Error::new();
        }

        $this->conn         = $conn;
        $this->lastInsertId = new LastInsertId();
    }

    /**
     * {@inheritdoc}
     */
    public function getServerVersion()
    {
        $serverInfo = sqlsrv_server_info($this->conn);

        return $serverInfo['SQLServerVersion'];
    }

    /**
     * {@inheritdoc}
     */
    public function requiresQueryForServerVersion()
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function prepare($sql)
    {
        return new Statement($this->conn, $sql, $this->lastInsertId);
    }

    /**
     * {@inheritDoc}
     */
    public function query()
    {
        $args = func_get_args();
        $sql  = $args[0];
        $stmt = $this->prepare($sql);
        $stmt->execute();

        return $stmt;
    }

    /**
     * {@inheritDoc}
     */
    public function quote($value, $type = ParameterType::STRING)
    {
        if (is_int($value)) {
            return $value;
        }

        if (is_float($value)) {
            return sprintf('%F', $value);
        }

        return "'" . str_replace("'", "''", $value) . "'";
    }

    /**
     * {@inheritDoc}
     */
    public function exec($sql)
    {
        $stmt = sqlsrv_query($this->conn, $sql);

        if ($stmt === false) {
            throw Error::new();
        }

        $rowsAffected = sqlsrv_rows_affected($stmt);

        if ($rowsAffected === false) {
            throw Error::new();
        }

        return $rowsAffected;
    }

    /**
     * {@inheritDoc}
     */
    public function lastInsertId($name = null)
    {
        if ($name !== null) {
            $stmt = $this->prepare('SELECT CONVERT(VARCHAR(MAX), current_value) FROM sys.sequences WHERE name = ?');
            $stmt->execute([$name]);
        } else {
            $stmt = $this->query('SELECT @@IDENTITY');
        }

        if ($stmt instanceof Result) {
            return $stmt->fetchOne();
        }

        return $stmt->fetchColumn();
    }

    /**
     * {@inheritDoc}
     */
    public function beginTransaction()
    {
        if (! sqlsrv_begin_transaction($this->conn)) {
            throw Error::new();
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function commit()
    {
        if (! sqlsrv_commit($this->conn)) {
            throw Error::new();
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function rollBack()
    {
        if (! sqlsrv_rollback($this->conn)) {
            throw Error::new();
        }

        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @deprecated The error information is available via exceptions.
     */
    public function errorCode()
    {
        $errors = sqlsrv_errors(SQLSRV_ERR_ERRORS);
        if ($errors) {
            return $errors[0]['code'];
        }

        return null;
    }

    /**
     * {@inheritDoc}
     *
     * @deprecated The error information is available via exceptions.
     */
    public function errorInfo()
    {
        return (array) sqlsrv_errors(SQLSRV_ERR_ERRORS);
    }
}
