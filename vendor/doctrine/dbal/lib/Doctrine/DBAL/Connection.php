<?php

namespace Doctrine\DBAL;

use Closure;
use Doctrine\Common\EventManager;
use Doctrine\DBAL\Abstraction\Result;
use Doctrine\DBAL\Cache\ArrayStatement;
use Doctrine\DBAL\Cache\CacheException;
use Doctrine\DBAL\Cache\QueryCacheProfile;
use Doctrine\DBAL\Cache\ResultCacheStatement;
use Doctrine\DBAL\Driver\Connection as DriverConnection;
use Doctrine\DBAL\Driver\PingableConnection;
use Doctrine\DBAL\Driver\ResultStatement;
use Doctrine\DBAL\Driver\ServerInfoAwareConnection;
use Doctrine\DBAL\Exception\ConnectionLost;
use Doctrine\DBAL\Exception\InvalidArgumentException;
use Doctrine\DBAL\Exception\NoKeyValue;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Query\Expression\ExpressionBuilder;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Types\Type;
use Throwable;
use Traversable;

use function array_key_exists;
use function array_shift;
use function assert;
use function func_get_args;
use function implode;
use function is_int;
use function is_string;
use function key;

/**
 * A wrapper around a Doctrine\DBAL\Driver\Connection that adds features like
 * events, transaction isolation levels, configuration, emulated transaction nesting,
 * lazy connecting and more.
 */
class Connection implements DriverConnection
{
    /**
     * Constant for transaction isolation level READ UNCOMMITTED.
     *
     * @deprecated Use TransactionIsolationLevel::READ_UNCOMMITTED.
     */
    public const TRANSACTION_READ_UNCOMMITTED = TransactionIsolationLevel::READ_UNCOMMITTED;

    /**
     * Constant for transaction isolation level READ COMMITTED.
     *
     * @deprecated Use TransactionIsolationLevel::READ_COMMITTED.
     */
    public const TRANSACTION_READ_COMMITTED = TransactionIsolationLevel::READ_COMMITTED;

    /**
     * Constant for transaction isolation level REPEATABLE READ.
     *
     * @deprecated Use TransactionIsolationLevel::REPEATABLE_READ.
     */
    public const TRANSACTION_REPEATABLE_READ = TransactionIsolationLevel::REPEATABLE_READ;

    /**
     * Constant for transaction isolation level SERIALIZABLE.
     *
     * @deprecated Use TransactionIsolationLevel::SERIALIZABLE.
     */
    public const TRANSACTION_SERIALIZABLE = TransactionIsolationLevel::SERIALIZABLE;

    /**
     * Represents an array of ints to be expanded by Doctrine SQL parsing.
     */
    public const PARAM_INT_ARRAY = ParameterType::INTEGER + self::ARRAY_PARAM_OFFSET;

    /**
     * Represents an array of strings to be expanded by Doctrine SQL parsing.
     */
    public const PARAM_STR_ARRAY = ParameterType::STRING + self::ARRAY_PARAM_OFFSET;

    /**
     * Offset by which PARAM_* constants are detected as arrays of the param type.
     */
    public const ARRAY_PARAM_OFFSET = 100;

    /**
     * The wrapped driver connection.
     *
     * @var \Doctrine\DBAL\Driver\Connection|null
     */
    protected $_conn;

    /** @var Configuration */
    protected $_config;

    /** @var EventManager */
    protected $_eventManager;

    /** @var ExpressionBuilder */
    protected $_expr;

    /**
     * The current auto-commit mode of this connection.
     *
     * @var bool
     */
    private $autoCommit = true;

    /**
     * The transaction nesting level.
     *
     * @var int
     */
    private $transactionNestingLevel = 0;

    /**
     * The currently active transaction isolation level.
     *
     * @var int
     */
    private $transactionIsolationLevel;

    /**
     * If nested transactions should use savepoints.
     *
     * @var bool
     */
    private $nestTransactionsWithSavepoints = false;

    /**
     * The parameters used during creation of the Connection instance.
     *
     * @var mixed[]
     */
    private $params = [];

    /**
     * The DatabasePlatform object that provides information about the
     * database platform used by the connection.
     *
     * @var AbstractPlatform
     */
    private $platform;

    /**
     * The schema manager.
     *
     * @var AbstractSchemaManager|null
     */
    protected $_schemaManager;

    /**
     * The used DBAL driver.
     *
     * @var Driver
     */
    protected $_driver;

    /**
     * Flag that indicates whether the current transaction is marked for rollback only.
     *
     * @var bool
     */
    private $isRollbackOnly = false;

    /** @var int */
    protected $defaultFetchMode = FetchMode::ASSOCIATIVE;

    /**
     * Initializes a new instance of the Connection class.
     *
     * @internal The connection can be only instantiated by the driver manager.
     *
     * @param mixed[]            $params       The connection parameters.
     * @param Driver             $driver       The driver to use.
     * @param Configuration|null $config       The configuration, optional.
     * @param EventManager|null  $eventManager The event manager, optional.
     *
     * @throws Exception
     */
    public function __construct(
        array $params,
        Driver $driver,
        ?Configuration $config = null,
        ?EventManager $eventManager = null
    ) {
        $this->_driver = $driver;
        $this->params  = $params;

        if (isset($params['pdo'])) {
            $this->_conn = $params['pdo'];
            unset($this->params['pdo']);
        }

        if (isset($params['platform'])) {
            if (! $params['platform'] instanceof Platforms\AbstractPlatform) {
                throw Exception::invalidPlatformType($params['platform']);
            }

            $this->platform = $params['platform'];
        }

        // Create default config and event manager if none given
        if (! $config) {
            $config = new Configuration();
        }

        if (! $eventManager) {
            $eventManager = new EventManager();
        }

        $this->_config       = $config;
        $this->_eventManager = $eventManager;

        $this->_expr = new Query\Expression\ExpressionBuilder($this);

        $this->autoCommit = $config->getAutoCommit();
    }

    /**
     * Gets the parameters used during instantiation.
     *
     * @internal
     *
     * @return mixed[]
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Gets the name of the database this Connection is connected to.
     *
     * @return string
     */
    public function getDatabase()
    {
        return $this->_driver->getDatabase($this);
    }

    /**
     * Gets the hostname of the currently connected database.
     *
     * @deprecated
     *
     * @return string|null
     */
    public function getHost()
    {
        return $this->params['host'] ?? null;
    }

    /**
     * Gets the port of the currently connected database.
     *
     * @deprecated
     *
     * @return mixed
     */
    public function getPort()
    {
        return $this->params['port'] ?? null;
    }

    /**
     * Gets the username used by this connection.
     *
     * @deprecated
     *
     * @return string|null
     */
    public function getUsername()
    {
        return $this->params['user'] ?? null;
    }

    /**
     * Gets the password used by this connection.
     *
     * @deprecated
     *
     * @return string|null
     */
    public function getPassword()
    {
        return $this->params['password'] ?? null;
    }

    /**
     * Gets the DBAL driver instance.
     *
     * @return Driver
     */
    public function getDriver()
    {
        return $this->_driver;
    }

    /**
     * Gets the Configuration used by the Connection.
     *
     * @return Configuration
     */
    public function getConfiguration()
    {
        return $this->_config;
    }

    /**
     * Gets the EventManager used by the Connection.
     *
     * @return EventManager
     */
    public function getEventManager()
    {
        return $this->_eventManager;
    }

    /**
     * Gets the DatabasePlatform for the connection.
     *
     * @return AbstractPlatform
     *
     * @throws Exception
     */
    public function getDatabasePlatform()
    {
        if ($this->platform === null) {
            $this->detectDatabasePlatform();
        }

        return $this->platform;
    }

    /**
     * Gets the ExpressionBuilder for the connection.
     *
     * @return ExpressionBuilder
     */
    public function getExpressionBuilder()
    {
        return $this->_expr;
    }

    /**
     * Establishes the connection with the database.
     *
     * @return bool TRUE if the connection was successfully established, FALSE if
     *              the connection is already open.
     */
    public function connect()
    {
        if ($this->_conn !== null) {
            return false;
        }

        $driverOptions = $this->params['driverOptions'] ?? [];
        $user          = $this->params['user'] ?? null;
        $password      = $this->params['password'] ?? null;

        $this->_conn = $this->_driver->connect($this->params, $user, $password, $driverOptions);

        $this->transactionNestingLevel = 0;

        if ($this->autoCommit === false) {
            $this->beginTransaction();
        }

        if ($this->_eventManager->hasListeners(Events::postConnect)) {
            $eventArgs = new Event\ConnectionEventArgs($this);
            $this->_eventManager->dispatchEvent(Events::postConnect, $eventArgs);
        }

        return true;
    }

    /**
     * Detects and sets the database platform.
     *
     * Evaluates custom platform class and version in order to set the correct platform.
     *
     * @throws Exception If an invalid platform was specified for this connection.
     */
    private function detectDatabasePlatform(): void
    {
        $version = $this->getDatabasePlatformVersion();

        if ($version !== null) {
            assert($this->_driver instanceof VersionAwarePlatformDriver);

            $this->platform = $this->_driver->createDatabasePlatformForVersion($version);
        } else {
            $this->platform = $this->_driver->getDatabasePlatform();
        }

        $this->platform->setEventManager($this->_eventManager);
    }

    /**
     * Returns the version of the related platform if applicable.
     *
     * Returns null if either the driver is not capable to create version
     * specific platform instances, no explicit server version was specified
     * or the underlying driver connection cannot determine the platform
     * version without having to query it (performance reasons).
     *
     * @return string|null
     *
     * @throws Throwable
     */
    private function getDatabasePlatformVersion()
    {
        // Driver does not support version specific platforms.
        if (! $this->_driver instanceof VersionAwarePlatformDriver) {
            return null;
        }

        // Explicit platform version requested (supersedes auto-detection).
        if (isset($this->params['serverVersion'])) {
            return $this->params['serverVersion'];
        }

        // If not connected, we need to connect now to determine the platform version.
        if ($this->_conn === null) {
            try {
                $this->connect();
            } catch (Throwable $originalException) {
                if (empty($this->params['dbname'])) {
                    throw $originalException;
                }

                // The database to connect to might not yet exist.
                // Retry detection without database name connection parameter.
                $databaseName           = $this->params['dbname'];
                $this->params['dbname'] = null;

                try {
                    $this->connect();
                } catch (Throwable $fallbackException) {
                    // Either the platform does not support database-less connections
                    // or something else went wrong.
                    // Reset connection parameters and rethrow the original exception.
                    $this->params['dbname'] = $databaseName;

                    throw $originalException;
                }

                // Reset connection parameters.
                $this->params['dbname'] = $databaseName;
                $serverVersion          = $this->getServerVersion();

                // Close "temporary" connection to allow connecting to the real database again.
                $this->close();

                return $serverVersion;
            }
        }

        return $this->getServerVersion();
    }

    /**
     * Returns the database server version if the underlying driver supports it.
     *
     * @return string|null
     */
    private function getServerVersion()
    {
        $connection = $this->getWrappedConnection();

        // Automatic platform version detection.
        if ($connection instanceof ServerInfoAwareConnection && ! $connection->requiresQueryForServerVersion()) {
            return $connection->getServerVersion();
        }

        // Unable to detect platform version.
        return null;
    }

    /**
     * Returns the current auto-commit mode for this connection.
     *
     * @see    setAutoCommit
     *
     * @return bool True if auto-commit mode is currently enabled for this connection, false otherwise.
     */
    public function isAutoCommit()
    {
        return $this->autoCommit === true;
    }

    /**
     * Sets auto-commit mode for this connection.
     *
     * If a connection is in auto-commit mode, then all its SQL statements will be executed and committed as individual
     * transactions. Otherwise, its SQL statements are grouped into transactions that are terminated by a call to either
     * the method commit or the method rollback. By default, new connections are in auto-commit mode.
     *
     * NOTE: If this method is called during a transaction and the auto-commit mode is changed, the transaction is
     * committed. If this method is called and the auto-commit mode is not changed, the call is a no-op.
     *
     * @see   isAutoCommit
     *
     * @param bool $autoCommit True to enable auto-commit mode; false to disable it.
     *
     * @return void
     */
    public function setAutoCommit($autoCommit)
    {
        $autoCommit = (bool) $autoCommit;

        // Mode not changed, no-op.
        if ($autoCommit === $this->autoCommit) {
            return;
        }

        $this->autoCommit = $autoCommit;

        // Commit all currently active transactions if any when switching auto-commit mode.
        if ($this->_conn === null || $this->transactionNestingLevel === 0) {
            return;
        }

        $this->commitAll();
    }

    /**
     * Sets the fetch mode.
     *
     * @deprecated Use one of the fetch- or iterate-related methods.
     *
     * @param int $fetchMode
     *
     * @return void
     */
    public function setFetchMode($fetchMode)
    {
        $this->defaultFetchMode = $fetchMode;
    }

    /**
     * Prepares and executes an SQL query and returns the first row of the result
     * as an associative array.
     *
     * @deprecated Use fetchAssociative()
     *
     * @param string                                                               $sql    SQL query
     * @param array<int, mixed>|array<string, mixed>                               $params Query parameters
     * @param array<int, int|string|Type|null>|array<string, int|string|Type|null> $types  Parameter types
     *
     * @return array<string, mixed>|false False is returned if no rows are found.
     *
     * @throws Exception
     */
    public function fetchAssoc($sql, array $params = [], array $types = [])
    {
        return $this->executeQuery($sql, $params, $types)->fetch(FetchMode::ASSOCIATIVE);
    }

    /**
     * Prepares and executes an SQL query and returns the first row of the result
     * as a numerically indexed array.
     *
     * @deprecated Use fetchNumeric()
     *
     * @param string                                                               $sql    SQL query
     * @param array<int, mixed>|array<string, mixed>                               $params Query parameters
     * @param array<int, int|string|Type|null>|array<string, int|string|Type|null> $types  Parameter types
     *
     * @return array<int, mixed>|false False is returned if no rows are found.
     */
    public function fetchArray($sql, array $params = [], array $types = [])
    {
        return $this->executeQuery($sql, $params, $types)->fetch(FetchMode::NUMERIC);
    }

    /**
     * Prepares and executes an SQL query and returns the value of a single column
     * of the first row of the result.
     *
     * @deprecated Use fetchOne() instead.
     *
     * @param string                                                               $sql    SQL query
     * @param array<int, mixed>|array<string, mixed>                               $params Query parameters
     * @param int                                                                  $column 0-indexed column number
     * @param array<int, int|string|Type|null>|array<string, int|string|Type|null> $types  Parameter types
     *
     * @return mixed|false False is returned if no rows are found.
     *
     * @throws Exception
     */
    public function fetchColumn($sql, array $params = [], $column = 0, array $types = [])
    {
        return $this->executeQuery($sql, $params, $types)->fetchColumn($column);
    }

    /**
     * Prepares and executes an SQL query and returns the first row of the result
     * as an associative array.
     *
     * @param string                                                               $query  SQL query
     * @param array<int, mixed>|array<string, mixed>                               $params Query parameters
     * @param array<int, int|string|Type|null>|array<string, int|string|Type|null> $types  Parameter types
     *
     * @return array<string, mixed>|false False is returned if no rows are found.
     *
     * @throws Exception
     */
    public function fetchAssociative(string $query, array $params = [], array $types = [])
    {
        try {
            $stmt = $this->executeQuery($query, $params, $types);

            if ($stmt instanceof Result) {
                return $stmt->fetchAssociative();
            }

            return $stmt->fetch(FetchMode::ASSOCIATIVE);
        } catch (Throwable $e) {
            $this->handleExceptionDuringQuery($e, $query, $params, $types);
        }
    }

    /**
     * Prepares and executes an SQL query and returns the first row of the result
     * as a numerically indexed array.
     *
     * @param string                                                               $query  SQL query
     * @param array<int, mixed>|array<string, mixed>                               $params Query parameters
     * @param array<int, int|string|Type|null>|array<string, int|string|Type|null> $types  Parameter types
     *
     * @return array<int, mixed>|false False is returned if no rows are found.
     *
     * @throws Exception
     */
    public function fetchNumeric(string $query, array $params = [], array $types = [])
    {
        try {
            $stmt = $this->executeQuery($query, $params, $types);

            if ($stmt instanceof Result) {
                return $stmt->fetchNumeric();
            }

            return $stmt->fetch(FetchMode::NUMERIC);
        } catch (Throwable $e) {
            $this->handleExceptionDuringQuery($e, $query, $params, $types);
        }
    }

    /**
     * Prepares and executes an SQL query and returns the value of a single column
     * of the first row of the result.
     *
     * @param string                                                               $query  SQL query
     * @param array<int, mixed>|array<string, mixed>                               $params Query parameters
     * @param array<int, int|string|Type|null>|array<string, int|string|Type|null> $types  Parameter types
     *
     * @return mixed|false False is returned if no rows are found.
     *
     * @throws Exception
     */
    public function fetchOne(string $query, array $params = [], array $types = [])
    {
        try {
            $stmt = $this->executeQuery($query, $params, $types);

            if ($stmt instanceof Result) {
                return $stmt->fetchOne();
            }

            return $stmt->fetch(FetchMode::COLUMN);
        } catch (Throwable $e) {
            $this->handleExceptionDuringQuery($e, $query, $params, $types);
        }
    }

    /**
     * Whether an actual connection to the database is established.
     *
     * @return bool
     */
    public function isConnected()
    {
        return $this->_conn !== null;
    }

    /**
     * Checks whether a transaction is currently active.
     *
     * @return bool TRUE if a transaction is currently active, FALSE otherwise.
     */
    public function isTransactionActive()
    {
        return $this->transactionNestingLevel > 0;
    }

    /**
     * Adds condition based on the criteria to the query components
     *
     * @param mixed[]  $criteria   Map of key columns to their values
     * @param string[] $columns    Column names
     * @param mixed[]  $values     Column values
     * @param string[] $conditions Key conditions
     *
     * @throws Exception
     */
    private function addCriteriaCondition(
        array $criteria,
        array &$columns,
        array &$values,
        array &$conditions
    ): void {
        $platform = $this->getDatabasePlatform();

        foreach ($criteria as $columnName => $value) {
            if ($value === null) {
                $conditions[] = $platform->getIsNullExpression($columnName);
                continue;
            }

            $columns[]    = $columnName;
            $values[]     = $value;
            $conditions[] = $columnName . ' = ?';
        }
    }

    /**
     * Executes an SQL DELETE statement on a table.
     *
     * Table expression and columns are not escaped and are not safe for user-input.
     *
     * @param string                                                               $table    Table name
     * @param array<string, mixed>                                                 $criteria Deletion criteria
     * @param array<int, int|string|Type|null>|array<string, int|string|Type|null> $types    Parameter types
     *
     * @return int The number of affected rows.
     *
     * @throws Exception
     */
    public function delete($table, array $criteria, array $types = [])
    {
        if (empty($criteria)) {
            throw InvalidArgumentException::fromEmptyCriteria();
        }

        $columns = $values = $conditions = [];

        $this->addCriteriaCondition($criteria, $columns, $values, $conditions);

        return $this->executeStatement(
            'DELETE FROM ' . $table . ' WHERE ' . implode(' AND ', $conditions),
            $values,
            is_string(key($types)) ? $this->extractTypeValues($columns, $types) : $types
        );
    }

    /**
     * Closes the connection.
     *
     * @return void
     */
    public function close()
    {
        $this->_conn = null;
    }

    /**
     * Sets the transaction isolation level.
     *
     * @param int $level The level to set.
     *
     * @return int
     */
    public function setTransactionIsolation($level)
    {
        $this->transactionIsolationLevel = $level;

        return $this->executeStatement($this->getDatabasePlatform()->getSetTransactionIsolationSQL($level));
    }

    /**
     * Gets the currently active transaction isolation level.
     *
     * @return int The current transaction isolation level.
     */
    public function getTransactionIsolation()
    {
        if ($this->transactionIsolationLevel === null) {
            $this->transactionIsolationLevel = $this->getDatabasePlatform()->getDefaultTransactionIsolationLevel();
        }

        return $this->transactionIsolationLevel;
    }

    /**
     * Executes an SQL UPDATE statement on a table.
     *
     * Table expression and columns are not escaped and are not safe for user-input.
     *
     * @param string                                                               $table    Table name
     * @param array<string, mixed>                                                 $data     Column-value pairs
     * @param array<string, mixed>                                                 $criteria Update criteria
     * @param array<int, int|string|Type|null>|array<string, int|string|Type|null> $types    Parameter types
     *
     * @return int The number of affected rows.
     *
     * @throws Exception
     */
    public function update($table, array $data, array $criteria, array $types = [])
    {
        $columns = $values = $conditions = $set = [];

        foreach ($data as $columnName => $value) {
            $columns[] = $columnName;
            $values[]  = $value;
            $set[]     = $columnName . ' = ?';
        }

        $this->addCriteriaCondition($criteria, $columns, $values, $conditions);

        if (is_string(key($types))) {
            $types = $this->extractTypeValues($columns, $types);
        }

        $sql = 'UPDATE ' . $table . ' SET ' . implode(', ', $set)
                . ' WHERE ' . implode(' AND ', $conditions);

        return $this->executeStatement($sql, $values, $types);
    }

    /**
     * Inserts a table row with specified data.
     *
     * Table expression and columns are not escaped and are not safe for user-input.
     *
     * @param string                                                               $table Table name
     * @param array<string, mixed>                                                 $data  Column-value pairs
     * @param array<int, int|string|Type|null>|array<string, int|string|Type|null> $types Parameter types
     *
     * @return int The number of affected rows.
     *
     * @throws Exception
     */
    public function insert($table, array $data, array $types = [])
    {
        if (empty($data)) {
            return $this->executeStatement('INSERT INTO ' . $table . ' () VALUES ()');
        }

        $columns = [];
        $values  = [];
        $set     = [];

        foreach ($data as $columnName => $value) {
            $columns[] = $columnName;
            $values[]  = $value;
            $set[]     = '?';
        }

        return $this->executeStatement(
            'INSERT INTO ' . $table . ' (' . implode(', ', $columns) . ')' .
            ' VALUES (' . implode(', ', $set) . ')',
            $values,
            is_string(key($types)) ? $this->extractTypeValues($columns, $types) : $types
        );
    }

    /**
     * Extract ordered type list from an ordered column list and type map.
     *
     * @param array<int, string>                                                   $columnList
     * @param array<int, int|string|Type|null>|array<string, int|string|Type|null> $types
     *
     * @return array<int, int|string|Type|null>|array<string, int|string|Type|null>
     */
    private function extractTypeValues(array $columnList, array $types)
    {
        $typeValues = [];

        foreach ($columnList as $columnIndex => $columnName) {
            $typeValues[] = $types[$columnName] ?? ParameterType::STRING;
        }

        return $typeValues;
    }

    /**
     * Quotes a string so it can be safely used as a table or column name, even if
     * it is a reserved name.
     *
     * Delimiting style depends on the underlying database platform that is being used.
     *
     * NOTE: Just because you CAN use quoted identifiers does not mean
     * you SHOULD use them. In general, they end up causing way more
     * problems than they solve.
     *
     * @param string $str The name to be quoted.
     *
     * @return string The quoted name.
     */
    public function quoteIdentifier($str)
    {
        return $this->getDatabasePlatform()->quoteIdentifier($str);
    }

    /**
     * {@inheritDoc}
     *
     * @param int|string|Type|null $type
     */
    public function quote($value, $type = ParameterType::STRING)
    {
        $connection = $this->getWrappedConnection();

        [$value, $bindingType] = $this->getBindingInfo($value, $type);

        return $connection->quote($value, $bindingType);
    }

    /**
     * Prepares and executes an SQL query and returns the result as an associative array.
     *
     * @deprecated Use fetchAllAssociative()
     *
     * @param string         $sql    The SQL query.
     * @param mixed[]        $params The query parameters.
     * @param int[]|string[] $types  The query parameter types.
     *
     * @return mixed[]
     */
    public function fetchAll($sql, array $params = [], $types = [])
    {
        return $this->executeQuery($sql, $params, $types)->fetchAll();
    }

    /**
     * Prepares and executes an SQL query and returns the result as an array of numeric arrays.
     *
     * @param string                                                               $query  SQL query
     * @param array<int, mixed>|array<string, mixed>                               $params Query parameters
     * @param array<int, int|string|Type|null>|array<string, int|string|Type|null> $types  Parameter types
     *
     * @return array<int,array<int,mixed>>
     *
     * @throws Exception
     */
    public function fetchAllNumeric(string $query, array $params = [], array $types = []): array
    {
        try {
            $stmt = $this->executeQuery($query, $params, $types);

            if ($stmt instanceof Result) {
                return $stmt->fetchAllNumeric();
            }

            return $stmt->fetchAll(FetchMode::NUMERIC);
        } catch (Throwable $e) {
            $this->handleExceptionDuringQuery($e, $query, $params, $types);
        }
    }

    /**
     * Prepares and executes an SQL query and returns the result as an array of associative arrays.
     *
     * @param string                                                               $query  SQL query
     * @param array<int, mixed>|array<string, mixed>                               $params Query parameters
     * @param array<int, int|string|Type|null>|array<string, int|string|Type|null> $types  Parameter types
     *
     * @return array<int,array<string,mixed>>
     *
     * @throws Exception
     */
    public function fetchAllAssociative(string $query, array $params = [], array $types = []): array
    {
        try {
            $stmt = $this->executeQuery($query, $params, $types);

            if ($stmt instanceof Result) {
                return $stmt->fetchAllAssociative();
            }

            return $stmt->fetchAll(FetchMode::ASSOCIATIVE);
        } catch (Throwable $e) {
            $this->handleExceptionDuringQuery($e, $query, $params, $types);
        }
    }

    /**
     * Prepares and executes an SQL query and returns the result as an associative array with the keys
     * mapped to the first column and the values mapped to the second column.
     *
     * @param string                                           $query  SQL query
     * @param array<int, mixed>|array<string, mixed>           $params Query parameters
     * @param array<int, int|string>|array<string, int|string> $types  Parameter types
     *
     * @return array<mixed,mixed>
     *
     * @throws Exception
     */
    public function fetchAllKeyValue(string $query, array $params = [], array $types = []): array
    {
        $stmt = $this->executeQuery($query, $params, $types);

        $this->ensureHasKeyValue($stmt);

        $data = [];

        foreach ($stmt->fetchAll(FetchMode::NUMERIC) as [$key, $value]) {
            $data[$key] = $value;
        }

        return $data;
    }

    /**
     * Prepares and executes an SQL query and returns the result as an associative array with the keys mapped
     * to the first column and the values being an associative array representing the rest of the columns
     * and their values.
     *
     * @param string                                           $query  SQL query
     * @param array<int, mixed>|array<string, mixed>           $params Query parameters
     * @param array<int, int|string>|array<string, int|string> $types  Parameter types
     *
     * @return array<mixed,array<string,mixed>>
     *
     * @throws Exception
     */
    public function fetchAllAssociativeIndexed(string $query, array $params = [], array $types = []): array
    {
        $stmt = $this->executeQuery($query, $params, $types);

        $data = [];

        foreach ($stmt->fetchAll(FetchMode::ASSOCIATIVE) as $row) {
            $data[array_shift($row)] = $row;
        }

        return $data;
    }

    /**
     * Prepares and executes an SQL query and returns the result as an array of the first column values.
     *
     * @param string                                                               $query  SQL query
     * @param array<int, mixed>|array<string, mixed>                               $params Query parameters
     * @param array<int, int|string|Type|null>|array<string, int|string|Type|null> $types  Parameter types
     *
     * @return array<int,mixed>
     *
     * @throws Exception
     */
    public function fetchFirstColumn(string $query, array $params = [], array $types = []): array
    {
        try {
            $stmt = $this->executeQuery($query, $params, $types);

            if ($stmt instanceof Result) {
                return $stmt->fetchFirstColumn();
            }

            return $stmt->fetchAll(FetchMode::COLUMN);
        } catch (Throwable $e) {
            $this->handleExceptionDuringQuery($e, $query, $params, $types);
        }
    }

    /**
     * Prepares and executes an SQL query and returns the result as an iterator over rows represented as numeric arrays.
     *
     * @param string                                                               $query  SQL query
     * @param array<int, mixed>|array<string, mixed>                               $params Query parameters
     * @param array<int, int|string|Type|null>|array<string, int|string|Type|null> $types  Parameter types
     *
     * @return Traversable<int,array<int,mixed>>
     *
     * @throws Exception
     */
    public function iterateNumeric(string $query, array $params = [], array $types = []): Traversable
    {
        try {
            $stmt = $this->executeQuery($query, $params, $types);

            if ($stmt instanceof Result) {
                yield from $stmt->iterateNumeric();
            } else {
                while (($row = $stmt->fetch(FetchMode::NUMERIC)) !== false) {
                    yield $row;
                }
            }
        } catch (Throwable $e) {
            $this->handleExceptionDuringQuery($e, $query, $params, $types);
        }
    }

    /**
     * Prepares and executes an SQL query and returns the result as an iterator over rows represented
     * as associative arrays.
     *
     * @param string                                                               $query  SQL query
     * @param array<int, mixed>|array<string, mixed>                               $params Query parameters
     * @param array<int, int|string|Type|null>|array<string, int|string|Type|null> $types  Parameter types
     *
     * @return Traversable<int,array<string,mixed>>
     *
     * @throws Exception
     */
    public function iterateAssociative(string $query, array $params = [], array $types = []): Traversable
    {
        try {
            $stmt = $this->executeQuery($query, $params, $types);

            if ($stmt instanceof Result) {
                yield from $stmt->iterateAssociative();
            } else {
                while (($row = $stmt->fetch(FetchMode::ASSOCIATIVE)) !== false) {
                    yield $row;
                }
            }
        } catch (Throwable $e) {
            $this->handleExceptionDuringQuery($e, $query, $params, $types);
        }
    }

    /**
     * Prepares and executes an SQL query and returns the result as an iterator with the keys
     * mapped to the first column and the values mapped to the second column.
     *
     * @param string                                           $query  SQL query
     * @param array<int, mixed>|array<string, mixed>           $params Query parameters
     * @param array<int, int|string>|array<string, int|string> $types  Parameter types
     *
     * @return Traversable<mixed,mixed>
     *
     * @throws Exception
     */
    public function iterateKeyValue(string $query, array $params = [], array $types = []): Traversable
    {
        $stmt = $this->executeQuery($query, $params, $types);

        $this->ensureHasKeyValue($stmt);

        while (($row = $stmt->fetch(FetchMode::NUMERIC)) !== false) {
            yield $row[0] => $row[1];
        }
    }

    /**
     * Prepares and executes an SQL query and returns the result as an iterator with the keys mapped
     * to the first column and the values being an associative array representing the rest of the columns
     * and their values.
     *
     * @param string                                           $query  SQL query
     * @param array<int, mixed>|array<string, mixed>           $params Query parameters
     * @param array<int, int|string>|array<string, int|string> $types  Parameter types
     *
     * @return Traversable<mixed,array<string,mixed>>
     *
     * @throws Exception
     */
    public function iterateAssociativeIndexed(string $query, array $params = [], array $types = []): Traversable
    {
        $stmt = $this->executeQuery($query, $params, $types);

        while (($row = $stmt->fetch(FetchMode::ASSOCIATIVE)) !== false) {
            yield array_shift($row) => $row;
        }
    }

    /**
     * Prepares and executes an SQL query and returns the result as an iterator over the first column values.
     *
     * @param string                                                               $query  SQL query
     * @param array<int, mixed>|array<string, mixed>                               $params Query parameters
     * @param array<int, int|string|Type|null>|array<string, int|string|Type|null> $types  Parameter types
     *
     * @return Traversable<int,mixed>
     *
     * @throws Exception
     */
    public function iterateColumn(string $query, array $params = [], array $types = []): Traversable
    {
        try {
            $stmt = $this->executeQuery($query, $params, $types);

            if ($stmt instanceof Result) {
                yield from $stmt->iterateColumn();
            } else {
                while (($value = $stmt->fetch(FetchMode::COLUMN)) !== false) {
                    yield $value;
                }
            }
        } catch (Throwable $e) {
            $this->handleExceptionDuringQuery($e, $query, $params, $types);
        }
    }

    /**
     * Prepares an SQL statement.
     *
     * @param string $sql The SQL statement to prepare.
     *
     * @return Statement The prepared statement.
     *
     * @throws Exception
     */
    public function prepare($sql)
    {
        try {
            $stmt = new Statement($sql, $this);
        } catch (Throwable $e) {
            $this->handleExceptionDuringQuery($e, $sql);
        }

        $stmt->setFetchMode($this->defaultFetchMode);

        return $stmt;
    }

    /**
     * Executes an, optionally parametrized, SQL query.
     *
     * If the query is parametrized, a prepared statement is used.
     * If an SQLLogger is configured, the execution is logged.
     *
     * @param string                                                               $sql    SQL query
     * @param array<int, mixed>|array<string, mixed>                               $params Query parameters
     * @param array<int, int|string|Type|null>|array<string, int|string|Type|null> $types  Parameter types
     *
     * @return ResultStatement The executed statement.
     *
     * @throws Exception
     */
    public function executeQuery($sql, array $params = [], $types = [], ?QueryCacheProfile $qcp = null)
    {
        if ($qcp !== null) {
            return $this->executeCacheQuery($sql, $params, $types, $qcp);
        }

        $connection = $this->getWrappedConnection();

        $logger = $this->_config->getSQLLogger();
        if ($logger) {
            $logger->startQuery($sql, $params, $types);
        }

        try {
            if ($params) {
                [$sql, $params, $types] = SQLParserUtils::expandListParameters($sql, $params, $types);

                $stmt = $connection->prepare($sql);
                if ($types) {
                    $this->_bindTypedValues($stmt, $params, $types);
                    $stmt->execute();
                } else {
                    $stmt->execute($params);
                }
            } else {
                $stmt = $connection->query($sql);
            }
        } catch (Throwable $e) {
            $this->handleExceptionDuringQuery(
                $e,
                $sql,
                $params,
                $types
            );
        }

        $stmt->setFetchMode($this->defaultFetchMode);

        if ($logger) {
            $logger->stopQuery();
        }

        return $stmt;
    }

    /**
     * Executes a caching query.
     *
     * @param string                                                               $sql    SQL query
     * @param array<int, mixed>|array<string, mixed>                               $params Query parameters
     * @param array<int, int|string|Type|null>|array<string, int|string|Type|null> $types  Parameter types
     *
     * @return ResultStatement
     *
     * @throws CacheException
     */
    public function executeCacheQuery($sql, $params, $types, QueryCacheProfile $qcp)
    {
        $resultCache = $qcp->getResultCacheDriver() ?? $this->_config->getResultCacheImpl();

        if ($resultCache === null) {
            throw CacheException::noResultDriverConfigured();
        }

        $connectionParams = $this->params;
        unset($connectionParams['platform']);

        [$cacheKey, $realKey] = $qcp->generateCacheKeys($sql, $params, $types, $connectionParams);

        // fetch the row pointers entry
        $data = $resultCache->fetch($cacheKey);

        if ($data !== false) {
            // is the real key part of this row pointers map or is the cache only pointing to other cache keys?
            if (isset($data[$realKey])) {
                $stmt = new ArrayStatement($data[$realKey]);
            } elseif (array_key_exists($realKey, $data)) {
                $stmt = new ArrayStatement([]);
            }
        }

        if (! isset($stmt)) {
            $stmt = new ResultCacheStatement(
                $this->executeQuery($sql, $params, $types),
                $resultCache,
                $cacheKey,
                $realKey,
                $qcp->getLifetime()
            );
        }

        $stmt->setFetchMode($this->defaultFetchMode);

        return $stmt;
    }

    /**
     * Executes an, optionally parametrized, SQL query and returns the result,
     * applying a given projection/transformation function on each row of the result.
     *
     * @deprecated
     *
     * @param string  $sql      The SQL query to execute.
     * @param mixed[] $params   The parameters, if any.
     * @param Closure $function The transformation function that is applied on each row.
     *                           The function receives a single parameter, an array, that
     *                           represents a row of the result set.
     *
     * @return mixed[] The projected result of the query.
     */
    public function project($sql, array $params, Closure $function)
    {
        $result = [];
        $stmt   = $this->executeQuery($sql, $params);

        while ($row = $stmt->fetch()) {
            $result[] = $function($row);
        }

        $stmt->closeCursor();

        return $result;
    }

    /**
     * Executes an SQL statement, returning a result set as a Statement object.
     *
     * @deprecated Use {@link executeQuery()} instead.
     *
     * @return \Doctrine\DBAL\Driver\Statement
     *
     * @throws Exception
     */
    public function query()
    {
        $connection = $this->getWrappedConnection();

        $args = func_get_args();

        $logger = $this->_config->getSQLLogger();
        if ($logger) {
            $logger->startQuery($args[0]);
        }

        try {
            $statement = $connection->query(...$args);
        } catch (Throwable $e) {
            $this->handleExceptionDuringQuery($e, $args[0]);
        }

        $statement->setFetchMode($this->defaultFetchMode);

        if ($logger) {
            $logger->stopQuery();
        }

        return $statement;
    }

    /**
     * Executes an SQL INSERT/UPDATE/DELETE query with the given parameters
     * and returns the number of affected rows.
     *
     * This method supports PDO binding types as well as DBAL mapping types.
     *
     * @deprecated Use {@link executeStatement()} instead.
     *
     * @param string                                                               $sql    SQL statement
     * @param array<int, mixed>|array<string, mixed>                               $params Statement parameters
     * @param array<int, int|string|Type|null>|array<string, int|string|Type|null> $types  Parameter types
     *
     * @return int The number of affected rows.
     *
     * @throws Exception
     */
    public function executeUpdate($sql, array $params = [], array $types = [])
    {
        return $this->executeStatement($sql, $params, $types);
    }

    /**
     * Executes an SQL statement with the given parameters and returns the number of affected rows.
     *
     * Could be used for:
     *  - DML statements: INSERT, UPDATE, DELETE, etc.
     *  - DDL statements: CREATE, DROP, ALTER, etc.
     *  - DCL statements: GRANT, REVOKE, etc.
     *  - Session control statements: ALTER SESSION, SET, DECLARE, etc.
     *  - Other statements that don't yield a row set.
     *
     * This method supports PDO binding types as well as DBAL mapping types.
     *
     * @param string                                                               $sql    SQL statement
     * @param array<int, mixed>|array<string, mixed>                               $params Statement parameters
     * @param array<int, int|string|Type|null>|array<string, int|string|Type|null> $types  Parameter types
     *
     * @return int The number of affected rows.
     *
     * @throws Exception
     */
    public function executeStatement($sql, array $params = [], array $types = [])
    {
        $connection = $this->getWrappedConnection();

        $logger = $this->_config->getSQLLogger();
        if ($logger) {
            $logger->startQuery($sql, $params, $types);
        }

        try {
            if ($params) {
                [$sql, $params, $types] = SQLParserUtils::expandListParameters($sql, $params, $types);

                $stmt = $connection->prepare($sql);

                if ($types) {
                    $this->_bindTypedValues($stmt, $params, $types);
                    $stmt->execute();
                } else {
                    $stmt->execute($params);
                }

                $result = $stmt->rowCount();
            } else {
                $result = $connection->exec($sql);
            }
        } catch (Throwable $e) {
            $this->handleExceptionDuringQuery(
                $e,
                $sql,
                $params,
                $types
            );
        }

        if ($logger) {
            $logger->stopQuery();
        }

        return $result;
    }

    /**
     * Executes an SQL statement and return the number of affected rows.
     *
     * @deprecated Use {@link executeStatement()} instead.
     *
     * @param string $sql
     *
     * @return int The number of affected rows.
     *
     * @throws Exception
     */
    public function exec($sql)
    {
        $connection = $this->getWrappedConnection();

        $logger = $this->_config->getSQLLogger();
        if ($logger) {
            $logger->startQuery($sql);
        }

        try {
            $result = $connection->exec($sql);
        } catch (Throwable $e) {
            $this->handleExceptionDuringQuery($e, $sql);
        }

        if ($logger) {
            $logger->stopQuery();
        }

        return $result;
    }

    /**
     * Returns the current transaction nesting level.
     *
     * @return int The nesting level. A value of 0 means there's no active transaction.
     */
    public function getTransactionNestingLevel()
    {
        return $this->transactionNestingLevel;
    }

    /**
     * Fetches the SQLSTATE associated with the last database operation.
     *
     * @deprecated The error information is available via exceptions.
     *
     * @return string|null The last error code.
     */
    public function errorCode()
    {
        return $this->getWrappedConnection()->errorCode();
    }

    /**
     * {@inheritDoc}
     *
     * @deprecated The error information is available via exceptions.
     */
    public function errorInfo()
    {
        return $this->getWrappedConnection()->errorInfo();
    }

    /**
     * Returns the ID of the last inserted row, or the last value from a sequence object,
     * depending on the underlying driver.
     *
     * Note: This method may not return a meaningful or consistent result across different drivers,
     * because the underlying database may not even support the notion of AUTO_INCREMENT/IDENTITY
     * columns or sequences.
     *
     * @param string|null $name Name of the sequence object from which the ID should be returned.
     *
     * @return string A string representation of the last inserted ID.
     */
    public function lastInsertId($name = null)
    {
        return $this->getWrappedConnection()->lastInsertId($name);
    }

    /**
     * Executes a function in a transaction.
     *
     * The function gets passed this Connection instance as an (optional) parameter.
     *
     * If an exception occurs during execution of the function or transaction commit,
     * the transaction is rolled back and the exception re-thrown.
     *
     * @param Closure $func The function to execute transactionally.
     *
     * @return mixed The value returned by $func
     *
     * @throws Throwable
     */
    public function transactional(Closure $func)
    {
        $this->beginTransaction();
        try {
            $res = $func($this);
            $this->commit();

            return $res;
        } catch (Throwable $e) {
            $this->rollBack();

            throw $e;
        }
    }

    /**
     * Sets if nested transactions should use savepoints.
     *
     * @param bool $nestTransactionsWithSavepoints
     *
     * @return void
     *
     * @throws ConnectionException
     */
    public function setNestTransactionsWithSavepoints($nestTransactionsWithSavepoints)
    {
        if ($this->transactionNestingLevel > 0) {
            throw ConnectionException::mayNotAlterNestedTransactionWithSavepointsInTransaction();
        }

        if (! $this->getDatabasePlatform()->supportsSavepoints()) {
            throw ConnectionException::savepointsNotSupported();
        }

        $this->nestTransactionsWithSavepoints = (bool) $nestTransactionsWithSavepoints;
    }

    /**
     * Gets if nested transactions should use savepoints.
     *
     * @return bool
     */
    public function getNestTransactionsWithSavepoints()
    {
        return $this->nestTransactionsWithSavepoints;
    }

    /**
     * Returns the savepoint name to use for nested transactions are false if they are not supported
     * "savepointFormat" parameter is not set
     *
     * @return mixed A string with the savepoint name or false.
     */
    protected function _getNestedTransactionSavePointName()
    {
        return 'DOCTRINE2_SAVEPOINT_' . $this->transactionNestingLevel;
    }

    /**
     * {@inheritDoc}
     */
    public function beginTransaction()
    {
        $connection = $this->getWrappedConnection();

        ++$this->transactionNestingLevel;

        $logger = $this->_config->getSQLLogger();

        if ($this->transactionNestingLevel === 1) {
            if ($logger) {
                $logger->startQuery('"START TRANSACTION"');
            }

            $connection->beginTransaction();

            if ($logger) {
                $logger->stopQuery();
            }
        } elseif ($this->nestTransactionsWithSavepoints) {
            if ($logger) {
                $logger->startQuery('"SAVEPOINT"');
            }

            $this->createSavepoint($this->_getNestedTransactionSavePointName());
            if ($logger) {
                $logger->stopQuery();
            }
        }

        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @throws ConnectionException If the commit failed due to no active transaction or
     *                                            because the transaction was marked for rollback only.
     */
    public function commit()
    {
        if ($this->transactionNestingLevel === 0) {
            throw ConnectionException::noActiveTransaction();
        }

        if ($this->isRollbackOnly) {
            throw ConnectionException::commitFailedRollbackOnly();
        }

        $result = true;

        $connection = $this->getWrappedConnection();

        $logger = $this->_config->getSQLLogger();

        if ($this->transactionNestingLevel === 1) {
            if ($logger) {
                $logger->startQuery('"COMMIT"');
            }

            $result = $connection->commit();

            if ($logger) {
                $logger->stopQuery();
            }
        } elseif ($this->nestTransactionsWithSavepoints) {
            if ($logger) {
                $logger->startQuery('"RELEASE SAVEPOINT"');
            }

            $this->releaseSavepoint($this->_getNestedTransactionSavePointName());
            if ($logger) {
                $logger->stopQuery();
            }
        }

        --$this->transactionNestingLevel;

        if ($this->autoCommit !== false || $this->transactionNestingLevel !== 0) {
            return $result;
        }

        $this->beginTransaction();

        return $result;
    }

    /**
     * Commits all current nesting transactions.
     */
    private function commitAll(): void
    {
        while ($this->transactionNestingLevel !== 0) {
            if ($this->autoCommit === false && $this->transactionNestingLevel === 1) {
                // When in no auto-commit mode, the last nesting commit immediately starts a new transaction.
                // Therefore we need to do the final commit here and then leave to avoid an infinite loop.
                $this->commit();

                return;
            }

            $this->commit();
        }
    }

    /**
     * Cancels any database changes done during the current transaction.
     *
     * @return bool
     *
     * @throws ConnectionException If the rollback operation failed.
     */
    public function rollBack()
    {
        if ($this->transactionNestingLevel === 0) {
            throw ConnectionException::noActiveTransaction();
        }

        $connection = $this->getWrappedConnection();

        $logger = $this->_config->getSQLLogger();

        if ($this->transactionNestingLevel === 1) {
            if ($logger) {
                $logger->startQuery('"ROLLBACK"');
            }

            $this->transactionNestingLevel = 0;
            $connection->rollBack();
            $this->isRollbackOnly = false;
            if ($logger) {
                $logger->stopQuery();
            }

            if ($this->autoCommit === false) {
                $this->beginTransaction();
            }
        } elseif ($this->nestTransactionsWithSavepoints) {
            if ($logger) {
                $logger->startQuery('"ROLLBACK TO SAVEPOINT"');
            }

            $this->rollbackSavepoint($this->_getNestedTransactionSavePointName());
            --$this->transactionNestingLevel;
            if ($logger) {
                $logger->stopQuery();
            }
        } else {
            $this->isRollbackOnly = true;
            --$this->transactionNestingLevel;
        }

        return true;
    }

    /**
     * Creates a new savepoint.
     *
     * @param string $savepoint The name of the savepoint to create.
     *
     * @return void
     *
     * @throws ConnectionException
     */
    public function createSavepoint($savepoint)
    {
        if (! $this->getDatabasePlatform()->supportsSavepoints()) {
            throw ConnectionException::savepointsNotSupported();
        }

        $this->getWrappedConnection()->exec($this->platform->createSavePoint($savepoint));
    }

    /**
     * Releases the given savepoint.
     *
     * @param string $savepoint The name of the savepoint to release.
     *
     * @return void
     *
     * @throws ConnectionException
     */
    public function releaseSavepoint($savepoint)
    {
        if (! $this->getDatabasePlatform()->supportsSavepoints()) {
            throw ConnectionException::savepointsNotSupported();
        }

        if (! $this->platform->supportsReleaseSavepoints()) {
            return;
        }

        $this->getWrappedConnection()->exec($this->platform->releaseSavePoint($savepoint));
    }

    /**
     * Rolls back to the given savepoint.
     *
     * @param string $savepoint The name of the savepoint to rollback to.
     *
     * @return void
     *
     * @throws ConnectionException
     */
    public function rollbackSavepoint($savepoint)
    {
        if (! $this->getDatabasePlatform()->supportsSavepoints()) {
            throw ConnectionException::savepointsNotSupported();
        }

        $this->getWrappedConnection()->exec($this->platform->rollbackSavePoint($savepoint));
    }

    /**
     * Gets the wrapped driver connection.
     *
     * @return DriverConnection
     */
    public function getWrappedConnection()
    {
        $this->connect();

        assert($this->_conn !== null);

        return $this->_conn;
    }

    /**
     * Gets the SchemaManager that can be used to inspect or change the
     * database schema through the connection.
     *
     * @return AbstractSchemaManager
     */
    public function getSchemaManager()
    {
        if ($this->_schemaManager === null) {
            $this->_schemaManager = $this->_driver->getSchemaManager($this);
        }

        return $this->_schemaManager;
    }

    /**
     * Marks the current transaction so that the only possible
     * outcome for the transaction to be rolled back.
     *
     * @return void
     *
     * @throws ConnectionException If no transaction is active.
     */
    public function setRollbackOnly()
    {
        if ($this->transactionNestingLevel === 0) {
            throw ConnectionException::noActiveTransaction();
        }

        $this->isRollbackOnly = true;
    }

    /**
     * Checks whether the current transaction is marked for rollback only.
     *
     * @return bool
     *
     * @throws ConnectionException If no transaction is active.
     */
    public function isRollbackOnly()
    {
        if ($this->transactionNestingLevel === 0) {
            throw ConnectionException::noActiveTransaction();
        }

        return $this->isRollbackOnly;
    }

    /**
     * Converts a given value to its database representation according to the conversion
     * rules of a specific DBAL mapping type.
     *
     * @param mixed  $value The value to convert.
     * @param string $type  The name of the DBAL mapping type.
     *
     * @return mixed The converted value.
     */
    public function convertToDatabaseValue($value, $type)
    {
        return Type::getType($type)->convertToDatabaseValue($value, $this->getDatabasePlatform());
    }

    /**
     * Converts a given value to its PHP representation according to the conversion
     * rules of a specific DBAL mapping type.
     *
     * @param mixed  $value The value to convert.
     * @param string $type  The name of the DBAL mapping type.
     *
     * @return mixed The converted type.
     */
    public function convertToPHPValue($value, $type)
    {
        return Type::getType($type)->convertToPHPValue($value, $this->getDatabasePlatform());
    }

    /**
     * Binds a set of parameters, some or all of which are typed with a PDO binding type
     * or DBAL mapping type, to a given statement.
     *
     * @internal Duck-typing used on the $stmt parameter to support driver statements as well as
     *           raw PDOStatement instances.
     *
     * @param \Doctrine\DBAL\Driver\Statement                                      $stmt   Prepared statement
     * @param array<int, mixed>|array<string, mixed>                               $params Statement parameters
     * @param array<int, int|string|Type|null>|array<string, int|string|Type|null> $types  Parameter types
     *
     * @return void
     */
    private function _bindTypedValues($stmt, array $params, array $types)
    {
        // Check whether parameters are positional or named. Mixing is not allowed, just like in PDO.
        if (is_int(key($params))) {
            // Positional parameters
            $typeOffset = array_key_exists(0, $types) ? -1 : 0;
            $bindIndex  = 1;
            foreach ($params as $value) {
                $typeIndex = $bindIndex + $typeOffset;
                if (isset($types[$typeIndex])) {
                    $type                  = $types[$typeIndex];
                    [$value, $bindingType] = $this->getBindingInfo($value, $type);
                    $stmt->bindValue($bindIndex, $value, $bindingType);
                } else {
                    $stmt->bindValue($bindIndex, $value);
                }

                ++$bindIndex;
            }
        } else {
            // Named parameters
            foreach ($params as $name => $value) {
                if (isset($types[$name])) {
                    $type                  = $types[$name];
                    [$value, $bindingType] = $this->getBindingInfo($value, $type);
                    $stmt->bindValue($name, $value, $bindingType);
                } else {
                    $stmt->bindValue($name, $value);
                }
            }
        }
    }

    /**
     * Gets the binding type of a given type. The given type can be a PDO or DBAL mapping type.
     *
     * @param mixed                $value The value to bind.
     * @param int|string|Type|null $type  The type to bind (PDO or DBAL).
     *
     * @return mixed[] [0] => the (escaped) value, [1] => the binding type.
     */
    private function getBindingInfo($value, $type)
    {
        if (is_string($type)) {
            $type = Type::getType($type);
        }

        if ($type instanceof Type) {
            $value       = $type->convertToDatabaseValue($value, $this->getDatabasePlatform());
            $bindingType = $type->getBindingType();
        } else {
            $bindingType = $type;
        }

        return [$value, $bindingType];
    }

    /**
     * Resolves the parameters to a format which can be displayed.
     *
     * @internal This is a purely internal method. If you rely on this method, you are advised to
     *           copy/paste the code as this method may change, or be removed without prior notice.
     *
     * @param array<int, mixed>|array<string, mixed>                               $params Query parameters
     * @param array<int, int|string|Type|null>|array<string, int|string|Type|null> $types  Parameter types
     *
     * @return array<int, int|string|Type|null>|array<string, int|string|Type|null>
     */
    public function resolveParams(array $params, array $types)
    {
        $resolvedParams = [];

        // Check whether parameters are positional or named. Mixing is not allowed, just like in PDO.
        if (is_int(key($params))) {
            // Positional parameters
            $typeOffset = array_key_exists(0, $types) ? -1 : 0;
            $bindIndex  = 1;
            foreach ($params as $value) {
                $typeIndex = $bindIndex + $typeOffset;
                if (isset($types[$typeIndex])) {
                    $type                       = $types[$typeIndex];
                    [$value]                    = $this->getBindingInfo($value, $type);
                    $resolvedParams[$bindIndex] = $value;
                } else {
                    $resolvedParams[$bindIndex] = $value;
                }

                ++$bindIndex;
            }
        } else {
            // Named parameters
            foreach ($params as $name => $value) {
                if (isset($types[$name])) {
                    $type                  = $types[$name];
                    [$value]               = $this->getBindingInfo($value, $type);
                    $resolvedParams[$name] = $value;
                } else {
                    $resolvedParams[$name] = $value;
                }
            }
        }

        return $resolvedParams;
    }

    /**
     * Creates a new instance of a SQL query builder.
     *
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        return new Query\QueryBuilder($this);
    }

    /**
     * Ping the server
     *
     * When the server is not available the method returns FALSE.
     * It is responsibility of the developer to handle this case
     * and abort the request or reconnect manually:
     *
     * @deprecated
     *
     * @return bool
     *
     * @example
     *
     *   if ($conn->ping() === false) {
     *      $conn->close();
     *      $conn->connect();
     *   }
     *
     * It is undefined if the underlying driver attempts to reconnect
     * or disconnect when the connection is not available anymore
     * as long it returns TRUE when a reconnect succeeded and
     * FALSE when the connection was dropped.
     */
    public function ping()
    {
        $connection = $this->getWrappedConnection();

        if ($connection instanceof PingableConnection) {
            return $connection->ping();
        }

        try {
            $this->query($this->getDatabasePlatform()->getDummySelectSQL());

            return true;
        } catch (DBALException $e) {
            return false;
        }
    }

    /**
     * @internal
     *
     * @param array<int, mixed>|array<string, mixed>                               $params
     * @param array<int, int|string|Type|null>|array<string, int|string|Type|null> $types
     *
     * @throws Exception
     *
     * @psalm-return never-return
     */
    public function handleExceptionDuringQuery(Throwable $e, string $sql, array $params = [], array $types = []): void
    {
        $this->throw(
            Exception::driverExceptionDuringQuery(
                $this->_driver,
                $e,
                $sql,
                $this->resolveParams($params, $types)
            )
        );
    }

    /**
     * @internal
     *
     * @throws Exception
     *
     * @psalm-return never-return
     */
    public function handleDriverException(Throwable $e): void
    {
        $this->throw(
            Exception::driverException(
                $this->_driver,
                $e
            )
        );
    }

    /**
     * @internal
     *
     * @throws Exception
     *
     * @psalm-return never-return
     */
    private function throw(Exception $e): void
    {
        if ($e instanceof ConnectionLost) {
            $this->close();
        }

        throw $e;
    }

    private function ensureHasKeyValue(ResultStatement $stmt): void
    {
        $columnCount = $stmt->columnCount();

        if ($columnCount < 2) {
            throw NoKeyValue::fromColumnCount($columnCount);
        }
    }
}
