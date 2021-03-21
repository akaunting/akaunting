<?php

namespace Doctrine\DBAL\Portability;

use Doctrine\DBAL\Driver\Result;
use Doctrine\DBAL\Driver\ResultStatement;
use Doctrine\DBAL\Driver\Statement as DriverStatement;
use Doctrine\DBAL\Driver\StatementIterator;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\ParameterType;
use IteratorAggregate;
use PDO;

use function array_change_key_case;
use function assert;
use function is_string;
use function rtrim;

/**
 * Portability wrapper for a Statement.
 */
class Statement implements IteratorAggregate, DriverStatement, Result
{
    /** @var int */
    private $portability;

    /** @var DriverStatement|ResultStatement */
    private $stmt;

    /** @var int */
    private $case;

    /** @var int */
    private $defaultFetchMode = FetchMode::MIXED;

    /**
     * Wraps <tt>Statement</tt> and applies portability measures.
     *
     * @param DriverStatement|ResultStatement $stmt
     */
    public function __construct($stmt, Connection $conn)
    {
        $this->stmt        = $stmt;
        $this->portability = $conn->getPortability();
        $this->case        = $conn->getFetchCase();
    }

    /**
     * {@inheritdoc}
     */
    public function bindParam($param, &$variable, $type = ParameterType::STRING, $length = null)
    {
        assert($this->stmt instanceof DriverStatement);

        return $this->stmt->bindParam($param, $variable, $type, $length);
    }

    /**
     * {@inheritdoc}
     */
    public function bindValue($param, $value, $type = ParameterType::STRING)
    {
        assert($this->stmt instanceof DriverStatement);

        return $this->stmt->bindValue($param, $value, $type);
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated Use free() instead.
     */
    public function closeCursor()
    {
        return $this->stmt->closeCursor();
    }

    /**
     * {@inheritdoc}
     */
    public function columnCount()
    {
        return $this->stmt->columnCount();
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated The error information is available via exceptions.
     */
    public function errorCode()
    {
        assert($this->stmt instanceof DriverStatement);

        return $this->stmt->errorCode();
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated The error information is available via exceptions.
     */
    public function errorInfo()
    {
        assert($this->stmt instanceof DriverStatement);

        return $this->stmt->errorInfo();
    }

    /**
     * {@inheritdoc}
     */
    public function execute($params = null)
    {
        assert($this->stmt instanceof DriverStatement);

        return $this->stmt->execute($params);
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated Use one of the fetch- or iterate-related methods.
     */
    public function setFetchMode($fetchMode, $arg2 = null, $arg3 = null)
    {
        $this->defaultFetchMode = $fetchMode;

        return $this->stmt->setFetchMode($fetchMode, $arg2, $arg3);
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

    /**
     * {@inheritdoc}
     *
     * @deprecated Use fetchNumeric(), fetchAssociative() or fetchOne() instead.
     */
    public function fetch($fetchMode = null, $cursorOrientation = PDO::FETCH_ORI_NEXT, $cursorOffset = 0)
    {
        $fetchMode = $fetchMode ?: $this->defaultFetchMode;

        $row = $this->stmt->fetch($fetchMode);

        $iterateRow = (
            $this->portability & (Connection::PORTABILITY_EMPTY_TO_NULL | Connection::PORTABILITY_RTRIM)
        ) !== 0;

        $fixCase = $this->case !== null
            && ($fetchMode === FetchMode::ASSOCIATIVE || $fetchMode === FetchMode::MIXED)
            && ($this->portability & Connection::PORTABILITY_FIX_CASE);

        $row = $this->fixRow($row, $iterateRow, $fixCase);

        return $row;
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated Use fetchAllNumeric(), fetchAllAssociative() or fetchFirstColumn() instead.
     */
    public function fetchAll($fetchMode = null, $fetchArgument = null, $ctorArgs = null)
    {
        $fetchMode = $fetchMode ?: $this->defaultFetchMode;

        if ($fetchArgument) {
            $rows = $this->stmt->fetchAll($fetchMode, $fetchArgument);
        } else {
            $rows = $this->stmt->fetchAll($fetchMode);
        }

        $fixCase = $this->case !== null
            && ($fetchMode === FetchMode::ASSOCIATIVE || $fetchMode === FetchMode::MIXED)
            && ($this->portability & Connection::PORTABILITY_FIX_CASE);

        return $this->fixResultSet($rows, $fixCase, $fetchMode !== FetchMode::COLUMN);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchNumeric()
    {
        if ($this->stmt instanceof Result) {
            $row = $this->stmt->fetchNumeric();
        } else {
            $row = $this->stmt->fetch(FetchMode::NUMERIC);
        }

        return $this->fixResult($row, false);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchAssociative()
    {
        if ($this->stmt instanceof Result) {
            $row = $this->stmt->fetchAssociative();
        } else {
            $row = $this->stmt->fetch(FetchMode::ASSOCIATIVE);
        }

        return $this->fixResult($row, true);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchOne()
    {
        if ($this->stmt instanceof Result) {
            $value = $this->stmt->fetchOne();
        } else {
            $value = $this->stmt->fetch(FetchMode::COLUMN);
        }

        if (($this->portability & Connection::PORTABILITY_EMPTY_TO_NULL) !== 0 && $value === '') {
            $value = null;
        } elseif (($this->portability & Connection::PORTABILITY_RTRIM) !== 0 && is_string($value)) {
            $value = rtrim($value);
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function fetchAllNumeric(): array
    {
        if ($this->stmt instanceof Result) {
            $data = $this->stmt->fetchAllNumeric();
        } else {
            $data = $this->stmt->fetchAll(FetchMode::NUMERIC);
        }

        return $this->fixResultSet($data, false, true);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchAllAssociative(): array
    {
        if ($this->stmt instanceof Result) {
            $data = $this->stmt->fetchAllAssociative();
        } else {
            $data = $this->stmt->fetchAll(FetchMode::ASSOCIATIVE);
        }

        return $this->fixResultSet($data, true, true);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchFirstColumn(): array
    {
        if ($this->stmt instanceof Result) {
            $data = $this->stmt->fetchFirstColumn();
        } else {
            $data = $this->stmt->fetchAll(FetchMode::COLUMN);
        }

        return $this->fixResultSet($data, true, false);
    }

    public function free(): void
    {
        if ($this->stmt instanceof Result) {
            $this->stmt->free();

            return;
        }

        $this->stmt->closeCursor();
    }

    /**
     * @param mixed $result
     *
     * @return mixed
     */
    private function fixResult($result, bool $fixCase)
    {
        $iterateRow = (
            $this->portability & (Connection::PORTABILITY_EMPTY_TO_NULL | Connection::PORTABILITY_RTRIM)
        ) !== 0;

        $fixCase = $fixCase && $this->case !== null && ($this->portability & Connection::PORTABILITY_FIX_CASE) !== 0;

        return $this->fixRow($result, $iterateRow, $fixCase);
    }

    /**
     * @param array<int,mixed> $resultSet
     *
     * @return array<int,mixed>
     */
    private function fixResultSet(array $resultSet, bool $fixCase, bool $isArray): array
    {
        $iterateRow = (
            $this->portability & (Connection::PORTABILITY_EMPTY_TO_NULL | Connection::PORTABILITY_RTRIM)
        ) !== 0;

        $fixCase = $fixCase && $this->case !== null && ($this->portability & Connection::PORTABILITY_FIX_CASE) !== 0;

        if (! $iterateRow && ! $fixCase) {
            return $resultSet;
        }

        if (! $isArray) {
            foreach ($resultSet as $num => $value) {
                $resultSet[$num] = [$value];
            }
        }

        foreach ($resultSet as $num => $row) {
            $resultSet[$num] = $this->fixRow($row, $iterateRow, $fixCase);
        }

        if (! $isArray) {
            foreach ($resultSet as $num => $row) {
                $resultSet[$num] = $row[0];
            }
        }

        return $resultSet;
    }

    /**
     * @param mixed $row
     * @param bool  $iterateRow
     * @param bool  $fixCase
     *
     * @return mixed
     */
    protected function fixRow($row, $iterateRow, $fixCase)
    {
        if (! $row) {
            return $row;
        }

        if ($fixCase) {
            $row = array_change_key_case($row, $this->case);
        }

        if ($iterateRow) {
            foreach ($row as $k => $v) {
                if (($this->portability & Connection::PORTABILITY_EMPTY_TO_NULL) && $v === '') {
                    $row[$k] = null;
                } elseif (($this->portability & Connection::PORTABILITY_RTRIM) && is_string($v)) {
                    $row[$k] = rtrim($v);
                }
            }
        }

        return $row;
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated Use fetchOne() instead.
     */
    public function fetchColumn($columnIndex = 0)
    {
        $value = $this->stmt->fetchColumn($columnIndex);

        if ($this->portability & (Connection::PORTABILITY_EMPTY_TO_NULL | Connection::PORTABILITY_RTRIM)) {
            if (($this->portability & Connection::PORTABILITY_EMPTY_TO_NULL) && $value === '') {
                $value = null;
            } elseif (($this->portability & Connection::PORTABILITY_RTRIM) && is_string($value)) {
                $value = rtrim($value);
            }
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function rowCount()
    {
        assert($this->stmt instanceof DriverStatement);

        return $this->stmt->rowCount();
    }
}
