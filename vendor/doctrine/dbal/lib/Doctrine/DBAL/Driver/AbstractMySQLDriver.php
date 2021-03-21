<?php

namespace Doctrine\DBAL\Driver;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Driver\DriverException as DeprecatedDriverException;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Exception\ConnectionException;
use Doctrine\DBAL\Exception\ConnectionLost;
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
use Doctrine\DBAL\Platforms\MariaDb1027Platform;
use Doctrine\DBAL\Platforms\MySQL57Platform;
use Doctrine\DBAL\Platforms\MySQL80Platform;
use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Schema\MySqlSchemaManager;
use Doctrine\DBAL\VersionAwarePlatformDriver;

use function assert;
use function preg_match;
use function stripos;
use function version_compare;

/**
 * Abstract base implementation of the {@link Driver} interface for MySQL based drivers.
 */
abstract class AbstractMySQLDriver implements Driver, ExceptionConverterDriver, VersionAwarePlatformDriver
{
    /**
     * {@inheritdoc}
     *
     * @deprecated
     *
     * @link https://dev.mysql.com/doc/refman/8.0/en/client-error-reference.html
     * @link https://dev.mysql.com/doc/refman/8.0/en/server-error-reference.html
     */
    public function convertException($message, DeprecatedDriverException $exception)
    {
        switch ($exception->getErrorCode()) {
            case '1213':
                return new DeadlockException($message, $exception);

            case '1205':
                return new LockWaitTimeoutException($message, $exception);

            case '1050':
                return new TableExistsException($message, $exception);

            case '1051':
            case '1146':
                return new TableNotFoundException($message, $exception);

            case '1216':
            case '1217':
            case '1451':
            case '1452':
            case '1701':
                return new ForeignKeyConstraintViolationException($message, $exception);

            case '1062':
            case '1557':
            case '1569':
            case '1586':
                return new UniqueConstraintViolationException($message, $exception);

            case '1054':
            case '1166':
            case '1611':
                return new InvalidFieldNameException($message, $exception);

            case '1052':
            case '1060':
            case '1110':
                return new NonUniqueFieldNameException($message, $exception);

            case '1064':
            case '1149':
            case '1287':
            case '1341':
            case '1342':
            case '1343':
            case '1344':
            case '1382':
            case '1479':
            case '1541':
            case '1554':
            case '1626':
                return new SyntaxErrorException($message, $exception);

            case '1044':
            case '1045':
            case '1046':
            case '1049':
            case '1095':
            case '1142':
            case '1143':
            case '1227':
            case '1370':
            case '1429':
            case '2002':
            case '2005':
                return new ConnectionException($message, $exception);

            case '2006':
                return new ConnectionLost($message, $exception);

            case '1048':
            case '1121':
            case '1138':
            case '1171':
            case '1252':
            case '1263':
            case '1364':
            case '1566':
                return new NotNullConstraintViolationException($message, $exception);
        }

        return new DriverException($message, $exception);
    }

    /**
     * {@inheritdoc}
     *
     * @throws Exception
     */
    public function createDatabasePlatformForVersion($version)
    {
        $mariadb = stripos($version, 'mariadb') !== false;
        if ($mariadb && version_compare($this->getMariaDbMysqlVersionNumber($version), '10.2.7', '>=')) {
            return new MariaDb1027Platform();
        }

        if (! $mariadb) {
            $oracleMysqlVersion = $this->getOracleMysqlVersionNumber($version);
            if (version_compare($oracleMysqlVersion, '8', '>=')) {
                return new MySQL80Platform();
            }

            if (version_compare($oracleMysqlVersion, '5.7.9', '>=')) {
                return new MySQL57Platform();
            }
        }

        return $this->getDatabasePlatform();
    }

    /**
     * Get a normalized 'version number' from the server string
     * returned by Oracle MySQL servers.
     *
     * @param string $versionString Version string returned by the driver, i.e. '5.7.10'
     *
     * @throws Exception
     */
    private function getOracleMysqlVersionNumber(string $versionString): string
    {
        if (
            ! preg_match(
                '/^(?P<major>\d+)(?:\.(?P<minor>\d+)(?:\.(?P<patch>\d+))?)?/',
                $versionString,
                $versionParts
            )
        ) {
            throw Exception::invalidPlatformVersionSpecified(
                $versionString,
                '<major_version>.<minor_version>.<patch_version>'
            );
        }

        $majorVersion = $versionParts['major'];
        $minorVersion = $versionParts['minor'] ?? 0;
        $patchVersion = $versionParts['patch'] ?? null;

        if ($majorVersion === '5' && $minorVersion === '7' && $patchVersion === null) {
            $patchVersion = '9';
        }

        return $majorVersion . '.' . $minorVersion . '.' . $patchVersion;
    }

    /**
     * Detect MariaDB server version, including hack for some mariadb distributions
     * that starts with the prefix '5.5.5-'
     *
     * @param string $versionString Version string as returned by mariadb server, i.e. '5.5.5-Mariadb-10.0.8-xenial'
     *
     * @throws Exception
     */
    private function getMariaDbMysqlVersionNumber(string $versionString): string
    {
        if (
            ! preg_match(
                '/^(?:5\.5\.5-)?(mariadb-)?(?P<major>\d+)\.(?P<minor>\d+)\.(?P<patch>\d+)/i',
                $versionString,
                $versionParts
            )
        ) {
            throw Exception::invalidPlatformVersionSpecified(
                $versionString,
                '^(?:5\.5\.5-)?(mariadb-)?<major_version>.<minor_version>.<patch_version>'
            );
        }

        return $versionParts['major'] . '.' . $versionParts['minor'] . '.' . $versionParts['patch'];
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

        $database = $conn->query('SELECT DATABASE()')->fetchColumn();

        assert($database !== false);

        return $database;
    }

    /**
     * {@inheritdoc}
     *
     * @return MySqlPlatform
     */
    public function getDatabasePlatform()
    {
        return new MySqlPlatform();
    }

    /**
     * {@inheritdoc}
     *
     * @return MySqlSchemaManager
     */
    public function getSchemaManager(Connection $conn)
    {
        return new MySqlSchemaManager($conn);
    }
}
