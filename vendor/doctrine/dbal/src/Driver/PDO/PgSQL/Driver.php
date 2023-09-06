<?php

namespace Doctrine\DBAL\Driver\PDO\PgSQL;

use Doctrine\DBAL\Driver\AbstractPostgreSQLDriver;
use Doctrine\DBAL\Driver\PDO\Connection;
use Doctrine\DBAL\Driver\PDO\Exception;
use Doctrine\Deprecations\Deprecation;
use PDO;
use PDOException;
use SensitiveParameter;

final class Driver extends AbstractPostgreSQLDriver
{
    /**
     * {@inheritDoc}
     *
     * @return Connection
     */
    public function connect(
        #[SensitiveParameter]
        array $params
    ) {
        $driverOptions = $params['driverOptions'] ?? [];

        if (! empty($params['persistent'])) {
            $driverOptions[PDO::ATTR_PERSISTENT] = true;
        }

        $safeParams = $params;
        unset($safeParams['password'], $safeParams['url']);

        try {
            $pdo = new PDO(
                $this->constructPdoDsn($safeParams),
                $params['user'] ?? '',
                $params['password'] ?? '',
                $driverOptions,
            );
        } catch (PDOException $exception) {
            throw Exception::new($exception);
        }

        if (
            ! isset($driverOptions[PDO::PGSQL_ATTR_DISABLE_PREPARES])
            || $driverOptions[PDO::PGSQL_ATTR_DISABLE_PREPARES] === true
        ) {
            $pdo->setAttribute(PDO::PGSQL_ATTR_DISABLE_PREPARES, true);
        }

        $connection = new Connection($pdo);

        /* defining client_encoding via SET NAMES to avoid inconsistent DSN support
         * - passing client_encoding via the 'options' param breaks pgbouncer support
         */
        if (isset($params['charset'])) {
            $connection->exec('SET NAMES \'' . $params['charset'] . '\'');
        }

        return $connection;
    }

    /**
     * Constructs the Postgres PDO DSN.
     *
     * @param array<string, mixed> $params
     */
    private function constructPdoDsn(array $params): string
    {
        $dsn = 'pgsql:';

        if (isset($params['host']) && $params['host'] !== '') {
            $dsn .= 'host=' . $params['host'] . ';';
        }

        if (isset($params['port']) && $params['port'] !== '') {
            $dsn .= 'port=' . $params['port'] . ';';
        }

        if (isset($params['dbname'])) {
            $dsn .= 'dbname=' . $params['dbname'] . ';';
        } elseif (isset($params['default_dbname'])) {
            Deprecation::trigger(
                'doctrine/dbal',
                'https://github.com/doctrine/dbal/pull/5705',
                'The "default_dbname" connection parameter is deprecated. Use "dbname" instead.',
            );

            $dsn .= 'dbname=' . $params['default_dbname'] . ';';
        } else {
            if (isset($params['user']) && $params['user'] !== 'postgres') {
                Deprecation::trigger(
                    'doctrine/dbal',
                    'https://github.com/doctrine/dbal/pull/5705',
                    'Relying on the DBAL connecting to the "postgres" database by default is deprecated.'
                        . ' Unless you want to have the server determine the default database for the connection,'
                        . ' specify the database name explicitly.',
                );
            }

            // Used for temporary connections to allow operations like dropping the database currently connected to.
            $dsn .= 'dbname=postgres;';
        }

        if (isset($params['sslmode'])) {
            $dsn .= 'sslmode=' . $params['sslmode'] . ';';
        }

        if (isset($params['sslrootcert'])) {
            $dsn .= 'sslrootcert=' . $params['sslrootcert'] . ';';
        }

        if (isset($params['sslcert'])) {
            $dsn .= 'sslcert=' . $params['sslcert'] . ';';
        }

        if (isset($params['sslkey'])) {
            $dsn .= 'sslkey=' . $params['sslkey'] . ';';
        }

        if (isset($params['sslcrl'])) {
            $dsn .= 'sslcrl=' . $params['sslcrl'] . ';';
        }

        if (isset($params['application_name'])) {
            $dsn .= 'application_name=' . $params['application_name'] . ';';
        }

        return $dsn;
    }
}
