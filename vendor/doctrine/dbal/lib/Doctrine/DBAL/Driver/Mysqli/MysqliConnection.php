<?php

namespace Doctrine\DBAL\Driver\Mysqli;

use Doctrine\DBAL\Driver\Connection as ConnectionInterface;
use Doctrine\DBAL\Driver\Mysqli\Exception\ConnectionError;
use Doctrine\DBAL\Driver\Mysqli\Exception\ConnectionFailed;
use Doctrine\DBAL\Driver\Mysqli\Exception\InvalidOption;
use Doctrine\DBAL\Driver\PingableConnection;
use Doctrine\DBAL\Driver\ServerInfoAwareConnection;
use Doctrine\DBAL\ParameterType;
use mysqli;

use function floor;
use function func_get_args;
use function in_array;
use function ini_get;
use function mysqli_errno;
use function mysqli_error;
use function mysqli_init;
use function mysqli_options;
use function restore_error_handler;
use function set_error_handler;
use function sprintf;
use function stripos;

use const MYSQLI_INIT_COMMAND;
use const MYSQLI_OPT_CONNECT_TIMEOUT;
use const MYSQLI_OPT_LOCAL_INFILE;
use const MYSQLI_OPT_READ_TIMEOUT;
use const MYSQLI_READ_DEFAULT_FILE;
use const MYSQLI_READ_DEFAULT_GROUP;
use const MYSQLI_SERVER_PUBLIC_KEY;

/**
 * @deprecated Use {@link Connection} instead
 */
class MysqliConnection implements ConnectionInterface, PingableConnection, ServerInfoAwareConnection
{
    /**
     * Name of the option to set connection flags
     */
    public const OPTION_FLAGS = 'flags';

    /** @var mysqli */
    private $conn;

    /**
     * @internal The connection can be only instantiated by its driver.
     *
     * @param mixed[] $params
     * @param string  $username
     * @param string  $password
     * @param mixed[] $driverOptions
     *
     * @throws MysqliException
     */
    public function __construct(array $params, $username, $password, array $driverOptions = [])
    {
        $port = $params['port'] ?? ini_get('mysqli.default_port');

        // Fallback to default MySQL port if not given.
        if (! $port) {
            $port = 3306;
        }

        $socket = $params['unix_socket'] ?? ini_get('mysqli.default_socket');
        $dbname = $params['dbname'] ?? null;

        $flags = $driverOptions[static::OPTION_FLAGS] ?? null;

        $this->conn = mysqli_init();

        $this->setSecureConnection($params);
        $this->setDriverOptions($driverOptions);

        set_error_handler(static function () {
        });
        try {
            if (! $this->conn->real_connect($params['host'], $username, $password, $dbname, $port, $socket, $flags)) {
                throw ConnectionFailed::new($this->conn);
            }
        } finally {
            restore_error_handler();
        }

        if (! isset($params['charset'])) {
            return;
        }

        $this->conn->set_charset($params['charset']);
    }

    /**
     * Retrieves mysqli native resource handle.
     *
     * Could be used if part of your application is not using DBAL.
     *
     * @return mysqli
     */
    public function getWrappedResourceHandle()
    {
        return $this->conn;
    }

    /**
     * {@inheritdoc}
     *
     * The server version detection includes a special case for MariaDB
     * to support '5.5.5-' prefixed versions introduced in Maria 10+
     *
     * @link https://jira.mariadb.org/browse/MDEV-4088
     */
    public function getServerVersion()
    {
        $serverInfos = $this->conn->get_server_info();
        if (stripos($serverInfos, 'mariadb') !== false) {
            return $serverInfos;
        }

        $majorVersion = floor($this->conn->server_version / 10000);
        $minorVersion = floor(($this->conn->server_version - $majorVersion * 10000) / 100);
        $patchVersion = floor($this->conn->server_version - $majorVersion * 10000 - $minorVersion * 100);

        return $majorVersion . '.' . $minorVersion . '.' . $patchVersion;
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
        return new Statement($this->conn, $sql);
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function quote($value, $type = ParameterType::STRING)
    {
        return "'" . $this->conn->escape_string($value) . "'";
    }

    /**
     * {@inheritdoc}
     */
    public function exec($sql)
    {
        if ($this->conn->query($sql) === false) {
            throw ConnectionError::new($this->conn);
        }

        return $this->conn->affected_rows;
    }

    /**
     * {@inheritdoc}
     */
    public function lastInsertId($name = null)
    {
        return $this->conn->insert_id;
    }

    /**
     * {@inheritdoc}
     */
    public function beginTransaction()
    {
        $this->conn->query('START TRANSACTION');

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function commit()
    {
        return $this->conn->commit();
    }

    /**
     * {@inheritdoc}non-PHPdoc)
     */
    public function rollBack()
    {
        return $this->conn->rollback();
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated The error information is available via exceptions.
     *
     * @return int
     */
    public function errorCode()
    {
        return $this->conn->errno;
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated The error information is available via exceptions.
     *
     * @return string
     */
    public function errorInfo()
    {
        return $this->conn->error;
    }

    /**
     * Apply the driver options to the connection.
     *
     * @param mixed[] $driverOptions
     *
     * @throws MysqliException When one of of the options is not supported.
     * @throws MysqliException When applying doesn't work - e.g. due to incorrect value.
     */
    private function setDriverOptions(array $driverOptions = []): void
    {
        $supportedDriverOptions = [
            MYSQLI_OPT_CONNECT_TIMEOUT,
            MYSQLI_OPT_LOCAL_INFILE,
            MYSQLI_OPT_READ_TIMEOUT,
            MYSQLI_INIT_COMMAND,
            MYSQLI_READ_DEFAULT_FILE,
            MYSQLI_READ_DEFAULT_GROUP,
            MYSQLI_SERVER_PUBLIC_KEY,
        ];

        $exceptionMsg = "%s option '%s' with value '%s'";

        foreach ($driverOptions as $option => $value) {
            if ($option === static::OPTION_FLAGS) {
                continue;
            }

            if (! in_array($option, $supportedDriverOptions, true)) {
                throw InvalidOption::fromOption($option, $value);
            }

            if (@mysqli_options($this->conn, $option, $value)) {
                continue;
            }

            $msg  = sprintf($exceptionMsg, 'Failed to set', $option, $value);
            $msg .= sprintf(', error: %s (%d)', mysqli_error($this->conn), mysqli_errno($this->conn));

            throw new MysqliException(
                $msg,
                $this->conn->sqlstate,
                $this->conn->errno
            );
        }
    }

    /**
     * Pings the server and re-connects when `mysqli.reconnect = 1`
     *
     * @deprecated
     *
     * @return bool
     */
    public function ping()
    {
        return $this->conn->ping();
    }

    /**
     * Establish a secure connection
     *
     * @param mixed[] $params
     *
     * @throws MysqliException
     */
    private function setSecureConnection(array $params): void
    {
        if (
            ! isset($params['ssl_key']) &&
            ! isset($params['ssl_cert']) &&
            ! isset($params['ssl_ca']) &&
            ! isset($params['ssl_capath']) &&
            ! isset($params['ssl_cipher'])
        ) {
            return;
        }

        $this->conn->ssl_set(
            $params['ssl_key']    ?? null,
            $params['ssl_cert']   ?? null,
            $params['ssl_ca']     ?? null,
            $params['ssl_capath'] ?? null,
            $params['ssl_cipher'] ?? null
        );
    }
}
