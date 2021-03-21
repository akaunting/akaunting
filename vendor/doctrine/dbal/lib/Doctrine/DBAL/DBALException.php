<?php

namespace Doctrine\DBAL;

use Doctrine\DBAL\Driver\DriverException as DeprecatedDriverException;
use Doctrine\DBAL\Driver\ExceptionConverterDriver;
use Doctrine\DBAL\Exception\DriverException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Throwable;

use function array_map;
use function bin2hex;
use function get_class;
use function gettype;
use function implode;
use function is_object;
use function is_resource;
use function is_string;
use function json_encode;
use function preg_replace;
use function spl_object_hash;
use function sprintf;

/**
 * @deprecated Use {@link Exception} instead
 *
 * @psalm-immutable
 */
class DBALException extends \Exception
{
    /**
     * @param string $method
     *
     * @return Exception
     */
    public static function notSupported($method)
    {
        return new Exception(sprintf("Operation '%s' is not supported by platform.", $method));
    }

    /**
     * @deprecated Use {@link invalidPlatformType()} instead.
     */
    public static function invalidPlatformSpecified(): self
    {
        return new Exception(
            "Invalid 'platform' option specified, need to give an instance of " . AbstractPlatform::class . '.'
        );
    }

    /**
     * @param mixed $invalidPlatform
     */
    public static function invalidPlatformType($invalidPlatform): self
    {
        if (is_object($invalidPlatform)) {
            return new Exception(
                sprintf(
                    "Option 'platform' must be a subtype of '%s', instance of '%s' given",
                    AbstractPlatform::class,
                    get_class($invalidPlatform)
                )
            );
        }

        return new Exception(
            sprintf(
                "Option 'platform' must be an object and subtype of '%s'. Got '%s'",
                AbstractPlatform::class,
                gettype($invalidPlatform)
            )
        );
    }

    /**
     * Returns a new instance for an invalid specified platform version.
     *
     * @param string $version        The invalid platform version given.
     * @param string $expectedFormat The expected platform version format.
     *
     * @return Exception
     */
    public static function invalidPlatformVersionSpecified($version, $expectedFormat)
    {
        return new Exception(
            sprintf(
                'Invalid platform version "%s" specified. ' .
                'The platform version has to be specified in the format: "%s".',
                $version,
                $expectedFormat
            )
        );
    }

    /**
     * @deprecated Passing a PDO instance in connection parameters is deprecated.
     *
     * @return Exception
     */
    public static function invalidPdoInstance()
    {
        return new Exception(
            "The 'pdo' option was used in DriverManager::getConnection() but no " .
            'instance of PDO was given.'
        );
    }

    /**
     * @param string|null $url The URL that was provided in the connection parameters (if any).
     *
     * @return Exception
     */
    public static function driverRequired($url = null)
    {
        if ($url) {
            return new Exception(
                sprintf(
                    "The options 'driver' or 'driverClass' are mandatory if a connection URL without scheme " .
                    'is given to DriverManager::getConnection(). Given URL: %s',
                    $url
                )
            );
        }

        return new Exception("The options 'driver' or 'driverClass' are mandatory if no PDO " .
            'instance is given to DriverManager::getConnection().');
    }

    /**
     * @param string   $unknownDriverName
     * @param string[] $knownDrivers
     *
     * @return Exception
     */
    public static function unknownDriver($unknownDriverName, array $knownDrivers)
    {
        return new Exception("The given 'driver' " . $unknownDriverName . ' is unknown, ' .
            'Doctrine currently supports only the following drivers: ' . implode(', ', $knownDrivers));
    }

    /**
     * @deprecated
     *
     * @param string  $sql
     * @param mixed[] $params
     *
     * @return Exception
     */
    public static function driverExceptionDuringQuery(Driver $driver, Throwable $driverEx, $sql, array $params = [])
    {
        $msg = "An exception occurred while executing '" . $sql . "'";
        if ($params) {
            $msg .= ' with params ' . self::formatParameters($params);
        }

        $msg .= ":\n\n" . $driverEx->getMessage();

        return static::wrapException($driver, $driverEx, $msg);
    }

    /**
     * @deprecated
     *
     * @return Exception
     */
    public static function driverException(Driver $driver, Throwable $driverEx)
    {
        return static::wrapException($driver, $driverEx, 'An exception occurred in driver: ' . $driverEx->getMessage());
    }

    /**
     * @return Exception
     */
    private static function wrapException(Driver $driver, Throwable $driverEx, string $msg)
    {
        if ($driverEx instanceof DriverException) {
            return $driverEx;
        }

        if ($driver instanceof ExceptionConverterDriver && $driverEx instanceof DeprecatedDriverException) {
            return $driver->convertException($msg, $driverEx);
        }

        return new Exception($msg, 0, $driverEx);
    }

    /**
     * Returns a human-readable representation of an array of parameters.
     * This properly handles binary data by returning a hex representation.
     *
     * @param mixed[] $params
     *
     * @return string
     */
    private static function formatParameters(array $params)
    {
        return '[' . implode(', ', array_map(static function ($param) {
            if (is_resource($param)) {
                return (string) $param;
            }

            $json = @json_encode($param);

            if (! is_string($json) || $json === 'null' && is_string($param)) {
                // JSON encoding failed, this is not a UTF-8 string.
                return sprintf('"%s"', preg_replace('/.{2}/', '\\x$0', bin2hex($param)));
            }

            return $json;
        }, $params)) . ']';
    }

    /**
     * @param string $wrapperClass
     *
     * @return Exception
     */
    public static function invalidWrapperClass($wrapperClass)
    {
        return new Exception("The given 'wrapperClass' " . $wrapperClass . ' has to be a ' .
            'subtype of \Doctrine\DBAL\Connection.');
    }

    /**
     * @param string $driverClass
     *
     * @return Exception
     */
    public static function invalidDriverClass($driverClass)
    {
        return new Exception(
            "The given 'driverClass' " . $driverClass . ' has to implement the ' . Driver::class . ' interface.'
        );
    }

    /**
     * @param string $tableName
     *
     * @return Exception
     */
    public static function invalidTableName($tableName)
    {
        return new Exception('Invalid table name specified: ' . $tableName);
    }

    /**
     * @param string $tableName
     *
     * @return Exception
     */
    public static function noColumnsSpecifiedForTable($tableName)
    {
        return new Exception('No columns specified for table ' . $tableName);
    }

    /**
     * @return Exception
     */
    public static function limitOffsetInvalid()
    {
        return new Exception('Invalid Offset in Limit Query, it has to be larger than or equal to 0.');
    }

    /**
     * @param string $name
     *
     * @return Exception
     */
    public static function typeExists($name)
    {
        return new Exception('Type ' . $name . ' already exists.');
    }

    /**
     * @param string $name
     *
     * @return Exception
     */
    public static function unknownColumnType($name)
    {
        return new Exception('Unknown column type "' . $name . '" requested. Any Doctrine type that you use has ' .
            'to be registered with \Doctrine\DBAL\Types\Type::addType(). You can get a list of all the ' .
            'known types with \Doctrine\DBAL\Types\Type::getTypesMap(). If this error occurs during database ' .
            'introspection then you might have forgotten to register all database types for a Doctrine Type. Use ' .
            'AbstractPlatform#registerDoctrineTypeMapping() or have your custom types implement ' .
            'Type#getMappedDatabaseTypes(). If the type name is empty you might ' .
            'have a problem with the cache or forgot some mapping information.');
    }

    /**
     * @param string $name
     *
     * @return Exception
     */
    public static function typeNotFound($name)
    {
        return new Exception('Type to be overwritten ' . $name . ' does not exist.');
    }

    public static function typeNotRegistered(Type $type): self
    {
        return new Exception(
            sprintf('Type of the class %s@%s is not registered.', get_class($type), spl_object_hash($type))
        );
    }

    public static function typeAlreadyRegistered(Type $type): self
    {
        return new Exception(
            sprintf('Type of the class %s@%s is already registered.', get_class($type), spl_object_hash($type))
        );
    }
}
