<?php

namespace Doctrine\DBAL\Driver\Mysqli;

use Doctrine\DBAL\Driver\FetchUtils;
use Doctrine\DBAL\Driver\Mysqli\Exception\ConnectionError;
use Doctrine\DBAL\Driver\Mysqli\Exception\FailedReadingStreamOffset;
use Doctrine\DBAL\Driver\Mysqli\Exception\StatementError;
use Doctrine\DBAL\Driver\Mysqli\Exception\UnknownType;
use Doctrine\DBAL\Driver\Result;
use Doctrine\DBAL\Driver\Statement as StatementInterface;
use Doctrine\DBAL\Driver\StatementIterator;
use Doctrine\DBAL\Exception\InvalidArgumentException;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\ParameterType;
use IteratorAggregate;
use mysqli;
use mysqli_stmt;
use PDO;

use function array_combine;
use function array_fill;
use function assert;
use function count;
use function feof;
use function fread;
use function get_resource_type;
use function is_array;
use function is_int;
use function is_resource;
use function sprintf;
use function str_repeat;

/**
 * @deprecated Use {@link Statement} instead
 */
class MysqliStatement implements IteratorAggregate, StatementInterface, Result
{
    /** @var string[] */
    protected static $_paramTypeMap = [
        ParameterType::ASCII        => 's',
        ParameterType::STRING       => 's',
        ParameterType::BINARY       => 's',
        ParameterType::BOOLEAN      => 'i',
        ParameterType::NULL         => 's',
        ParameterType::INTEGER      => 'i',
        ParameterType::LARGE_OBJECT => 'b',
    ];

    /** @var mysqli */
    protected $_conn;

    /** @var mysqli_stmt */
    protected $_stmt;

    /** @var string[]|false|null */
    protected $_columnNames;

    /** @var mixed[] */
    protected $_rowBindedValues = [];

    /** @var mixed[] */
    protected $_bindedValues;

    /** @var string */
    protected $types;

    /**
     * Contains ref values for bindValue().
     *
     * @var mixed[]
     */
    protected $_values = [];

    /** @var int */
    protected $_defaultFetchMode = FetchMode::MIXED;

    /**
     * Indicates whether the statement is in the state when fetching results is possible
     *
     * @var bool
     */
    private $result = false;

    /**
     * @internal The statement can be only instantiated by its driver connection.
     *
     * @param string $prepareString
     *
     * @throws MysqliException
     */
    public function __construct(mysqli $conn, $prepareString)
    {
        $this->_conn = $conn;

        $stmt = $conn->prepare($prepareString);

        if ($stmt === false) {
            throw ConnectionError::new($this->_conn);
        }

        $this->_stmt = $stmt;

        $paramCount = $this->_stmt->param_count;
        if (0 >= $paramCount) {
            return;
        }

        $this->types         = str_repeat('s', $paramCount);
        $this->_bindedValues = array_fill(1, $paramCount, null);
    }

    /**
     * {@inheritdoc}
     */
    public function bindParam($param, &$variable, $type = ParameterType::STRING, $length = null)
    {
        assert(is_int($param));

        if (! isset(self::$_paramTypeMap[$type])) {
            throw UnknownType::new($type);
        }

        $this->_bindedValues[$param] =& $variable;
        $this->types[$param - 1]     = self::$_paramTypeMap[$type];

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function bindValue($param, $value, $type = ParameterType::STRING)
    {
        assert(is_int($param));

        if (! isset(self::$_paramTypeMap[$type])) {
            throw UnknownType::new($type);
        }

        $this->_values[$param]       = $value;
        $this->_bindedValues[$param] =& $this->_values[$param];
        $this->types[$param - 1]     = self::$_paramTypeMap[$type];

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function execute($params = null)
    {
        if ($this->_bindedValues !== null) {
            if ($params !== null) {
                if (! $this->bindUntypedValues($params)) {
                    throw StatementError::new($this->_stmt);
                }
            } else {
                $this->bindTypedParameters();
            }
        }

        if (! $this->_stmt->execute()) {
            throw StatementError::new($this->_stmt);
        }

        if ($this->_columnNames === null) {
            $meta = $this->_stmt->result_metadata();
            if ($meta !== false) {
                $fields = $meta->fetch_fields();
                assert(is_array($fields));

                $columnNames = [];
                foreach ($fields as $col) {
                    $columnNames[] = $col->name;
                }

                $meta->free();

                $this->_columnNames = $columnNames;
            } else {
                $this->_columnNames = false;
            }
        }

        if ($this->_columnNames !== false) {
            // Store result of every execution which has it. Otherwise it will be impossible
            // to execute a new statement in case if the previous one has non-fetched rows
            // @link http://dev.mysql.com/doc/refman/5.7/en/commands-out-of-sync.html
            $this->_stmt->store_result();

            // Bind row values _after_ storing the result. Otherwise, if mysqli is compiled with libmysql,
            // it will have to allocate as much memory as it may be needed for the given column type
            // (e.g. for a LONGBLOB column it's 4 gigabytes)
            // @link https://bugs.php.net/bug.php?id=51386#1270673122
            //
            // Make sure that the values are bound after each execution. Otherwise, if closeCursor() has been
            // previously called on the statement, the values are unbound making the statement unusable.
            //
            // It's also important that row values are bound after _each_ call to store_result(). Otherwise,
            // if mysqli is compiled with libmysql, subsequently fetched string values will get truncated
            // to the length of the ones fetched during the previous execution.
            $this->_rowBindedValues = array_fill(0, count($this->_columnNames), null);

            $refs = [];
            foreach ($this->_rowBindedValues as $key => &$value) {
                $refs[$key] =& $value;
            }

            if (! $this->_stmt->bind_result(...$refs)) {
                throw StatementError::new($this->_stmt);
            }
        }

        $this->result = true;

        return true;
    }

    /**
     * Binds parameters with known types previously bound to the statement
     */
    private function bindTypedParameters(): void
    {
        $streams = $values = [];
        $types   = $this->types;

        foreach ($this->_bindedValues as $parameter => $value) {
            assert(is_int($parameter));

            if (! isset($types[$parameter - 1])) {
                $types[$parameter - 1] = static::$_paramTypeMap[ParameterType::STRING];
            }

            if ($types[$parameter - 1] === static::$_paramTypeMap[ParameterType::LARGE_OBJECT]) {
                if (is_resource($value)) {
                    if (get_resource_type($value) !== 'stream') {
                        throw new InvalidArgumentException(
                            'Resources passed with the LARGE_OBJECT parameter type must be stream resources.'
                        );
                    }

                    $streams[$parameter] = $value;
                    $values[$parameter]  = null;
                    continue;
                }

                $types[$parameter - 1] = static::$_paramTypeMap[ParameterType::STRING];
            }

            $values[$parameter] = $value;
        }

        if (! $this->_stmt->bind_param($types, ...$values)) {
            throw StatementError::new($this->_stmt);
        }

        $this->sendLongData($streams);
    }

    /**
     * Handle $this->_longData after regular query parameters have been bound
     *
     * @param array<int, resource> $streams
     *
     * @throws MysqliException
     */
    private function sendLongData(array $streams): void
    {
        foreach ($streams as $paramNr => $stream) {
            while (! feof($stream)) {
                $chunk = fread($stream, 8192);

                if ($chunk === false) {
                    throw FailedReadingStreamOffset::new($paramNr);
                }

                if (! $this->_stmt->send_long_data($paramNr - 1, $chunk)) {
                    throw StatementError::new($this->_stmt);
                }
            }
        }
    }

    /**
     * Binds a array of values to bound parameters.
     *
     * @param mixed[] $values
     *
     * @return bool
     */
    private function bindUntypedValues(array $values)
    {
        $params = [];
        $types  = str_repeat('s', count($values));

        foreach ($values as &$v) {
            $params[] =& $v;
        }

        return $this->_stmt->bind_param($types, ...$params);
    }

    /**
     * @return mixed[]|false|null
     */
    private function _fetch()
    {
        $ret = $this->_stmt->fetch();

        if ($ret === true) {
            $values = [];
            foreach ($this->_rowBindedValues as $v) {
                $values[] = $v;
            }

            return $values;
        }

        return $ret;
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated Use fetchNumeric(), fetchAssociative() or fetchOne() instead.
     */
    public function fetch($fetchMode = null, $cursorOrientation = PDO::FETCH_ORI_NEXT, $cursorOffset = 0)
    {
        // do not try fetching from the statement if it's not expected to contain result
        // in order to prevent exceptional situation
        if (! $this->result) {
            return false;
        }

        $fetchMode = $fetchMode ?: $this->_defaultFetchMode;

        if ($fetchMode === FetchMode::COLUMN) {
            return $this->fetchColumn();
        }

        $values = $this->_fetch();

        if ($values === null) {
            return false;
        }

        if ($values === false) {
            throw StatementError::new($this->_stmt);
        }

        if ($fetchMode === FetchMode::NUMERIC) {
            return $values;
        }

        assert(is_array($this->_columnNames));
        $assoc = array_combine($this->_columnNames, $values);
        assert(is_array($assoc));

        switch ($fetchMode) {
            case FetchMode::ASSOCIATIVE:
                return $assoc;

            case FetchMode::MIXED:
                return $assoc + $values;

            case FetchMode::STANDARD_OBJECT:
                return (object) $assoc;

            default:
                throw new MysqliException(sprintf("Unknown fetch type '%s'", $fetchMode));
        }
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated Use fetchAllNumeric(), fetchAllAssociative() or fetchFirstColumn() instead.
     */
    public function fetchAll($fetchMode = null, $fetchArgument = null, $ctorArgs = null)
    {
        $fetchMode = $fetchMode ?: $this->_defaultFetchMode;

        $rows = [];

        if ($fetchMode === FetchMode::COLUMN) {
            while (($row = $this->fetchColumn()) !== false) {
                $rows[] = $row;
            }
        } else {
            while (($row = $this->fetch($fetchMode)) !== false) {
                $rows[] = $row;
            }
        }

        return $rows;
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated Use fetchOne() instead.
     */
    public function fetchColumn($columnIndex = 0)
    {
        $row = $this->fetch(FetchMode::NUMERIC);

        if ($row === false) {
            return false;
        }

        return $row[$columnIndex] ?? null;
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated The error information is available via exceptions.
     */
    public function fetchNumeric()
    {
        // do not try fetching from the statement if it's not expected to contain the result
        // in order to prevent exceptional situation
        if (! $this->result) {
            return false;
        }

        $values = $this->_fetch();

        if ($values === null) {
            return false;
        }

        if ($values === false) {
            throw StatementError::new($this->_stmt);
        }

        return $values;
    }

    /**
     * {@inheritDoc}
     */
    public function fetchAssociative()
    {
        $values = $this->fetchNumeric();

        if ($values === false) {
            return false;
        }

        assert(is_array($this->_columnNames));
        $row = array_combine($this->_columnNames, $values);
        assert(is_array($row));

        return $row;
    }

    /**
     * {@inheritdoc}
     */
    public function fetchOne()
    {
        return FetchUtils::fetchOne($this);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchAllNumeric(): array
    {
        return FetchUtils::fetchAllNumeric($this);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchAllAssociative(): array
    {
        return FetchUtils::fetchAllAssociative($this);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchFirstColumn(): array
    {
        return FetchUtils::fetchFirstColumn($this);
    }

    /**
     * {@inheritdoc}
     */
    public function errorCode()
    {
        return $this->_stmt->errno;
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
        return $this->_stmt->error;
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated Use free() instead.
     */
    public function closeCursor()
    {
        $this->free();

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rowCount()
    {
        if ($this->_columnNames === false) {
            return $this->_stmt->affected_rows;
        }

        return $this->_stmt->num_rows;
    }

    /**
     * {@inheritdoc}
     */
    public function columnCount()
    {
        return $this->_stmt->field_count;
    }

    public function free(): void
    {
        $this->_stmt->free_result();
        $this->result = false;
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated Use one of the fetch- or iterate-related methods.
     */
    public function setFetchMode($fetchMode, $arg2 = null, $arg3 = null)
    {
        $this->_defaultFetchMode = $fetchMode;

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated Use iterateNumeric(), iterateAssociative() or iterateColumn() instead.
     */
    public function getIterator()
    {
        return new StatementIterator($this);
    }
}
