<?php

namespace Doctrine\DBAL\Driver\OCI8;

use Doctrine\DBAL\Driver\Connection as ConnectionInterface;
use Doctrine\DBAL\Driver\OCI8\Exception\SequenceDoesNotExist;
use Doctrine\DBAL\Driver\ServerInfoAwareConnection;
use Doctrine\DBAL\ParameterType;
use UnexpectedValueException;

use function addcslashes;
use function func_get_args;
use function is_float;
use function is_int;
use function oci_commit;
use function oci_connect;
use function oci_error;
use function oci_pconnect;
use function oci_rollback;
use function oci_server_version;
use function preg_match;
use function sprintf;
use function str_replace;

use const OCI_COMMIT_ON_SUCCESS;
use const OCI_NO_AUTO_COMMIT;

/**
 * OCI8 implementation of the Connection interface.
 *
 * @deprecated Use {@link Connection} instead
 */
class OCI8Connection implements ConnectionInterface, ServerInfoAwareConnection
{
    /** @var resource */
    protected $dbh;

    /** @var int */
    protected $executeMode = OCI_COMMIT_ON_SUCCESS;

    /**
     * Creates a Connection to an Oracle Database using oci8 extension.
     *
     * @internal The connection can be only instantiated by its driver.
     *
     * @param string $username
     * @param string $password
     * @param string $db
     * @param string $charset
     * @param int    $sessionMode
     * @param bool   $persistent
     *
     * @throws OCI8Exception
     */
    public function __construct(
        $username,
        $password,
        $db,
        $charset = '',
        $sessionMode = OCI_NO_AUTO_COMMIT,
        $persistent = false
    ) {
        $dbh = $persistent
            ? @oci_pconnect($username, $password, $db, $charset, $sessionMode)
            : @oci_connect($username, $password, $db, $charset, $sessionMode);

        if ($dbh === false) {
            throw OCI8Exception::fromErrorInfo(oci_error());
        }

        $this->dbh = $dbh;
    }

    /**
     * {@inheritdoc}
     *
     * @throws UnexpectedValueException If the version string returned by the database server
     *                                  does not contain a parsable version number.
     */
    public function getServerVersion()
    {
        $version = oci_server_version($this->dbh);

        if ($version === false) {
            throw OCI8Exception::fromErrorInfo(oci_error($this->dbh));
        }

        if (! preg_match('/\s+(\d+\.\d+\.\d+\.\d+\.\d+)\s+/', $version, $matches)) {
            throw new UnexpectedValueException(
                sprintf(
                    'Unexpected database version string "%s". Cannot parse an appropriate version number from it. ' .
                    'Please report this database version string to the Doctrine team.',
                    $version
                )
            );
        }

        return $matches[1];
    }

    /**
     * {@inheritdoc}
     */
    public function requiresQueryForServerVersion()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function prepare($sql)
    {
        return new Statement($this->dbh, $sql, $this);
    }

    /**
     * {@inheritdoc}
     */
    public function query()
    {
        $args = func_get_args();
        $sql  = $args[0];
        //$fetchMode = $args[1];
        $stmt = $this->prepare($sql);
        $stmt->execute();

        return $stmt;
    }

    /**
     * {@inheritdoc}
     */
    public function quote($value, $type = ParameterType::STRING)
    {
        if (is_int($value) || is_float($value)) {
            return $value;
        }

        $value = str_replace("'", "''", $value);

        return "'" . addcslashes($value, "\000\n\r\\\032") . "'";
    }

    /**
     * {@inheritdoc}
     */
    public function exec($sql)
    {
        $stmt = $this->prepare($sql);
        $stmt->execute();

        return $stmt->rowCount();
    }

    /**
     * {@inheritdoc}
     *
     * @return int|false
     */
    public function lastInsertId($name = null)
    {
        if ($name === null) {
            return false;
        }

        $sql    = 'SELECT ' . $name . '.CURRVAL FROM DUAL';
        $stmt   = $this->query($sql);
        $result = $stmt->fetchColumn();

        if ($result === false) {
            throw SequenceDoesNotExist::new();
        }

        return (int) $result;
    }

    /**
     * Returns the current execution mode.
     *
     * @internal
     *
     * @return int
     */
    public function getExecuteMode()
    {
        return $this->executeMode;
    }

    /**
     * {@inheritdoc}
     */
    public function beginTransaction()
    {
        $this->executeMode = OCI_NO_AUTO_COMMIT;

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function commit()
    {
        if (! oci_commit($this->dbh)) {
            throw OCI8Exception::fromErrorInfo($this->errorInfo());
        }

        $this->executeMode = OCI_COMMIT_ON_SUCCESS;

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rollBack()
    {
        if (! oci_rollback($this->dbh)) {
            throw OCI8Exception::fromErrorInfo($this->errorInfo());
        }

        $this->executeMode = OCI_COMMIT_ON_SUCCESS;

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated The error information is available via exceptions.
     */
    public function errorCode()
    {
        $error = oci_error($this->dbh);

        if ($error !== false) {
            return $error['code'];
        }

        return null;
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated The error information is available via exceptions.
     */
    public function errorInfo()
    {
        $error = oci_error($this->dbh);

        if ($error === false) {
            return [];
        }

        return $error;
    }
}
