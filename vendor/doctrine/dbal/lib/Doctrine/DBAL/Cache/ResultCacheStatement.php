<?php

namespace Doctrine\DBAL\Cache;

use ArrayIterator;
use Doctrine\Common\Cache\Cache;
use Doctrine\DBAL\Driver\Exception;
use Doctrine\DBAL\Driver\FetchUtils;
use Doctrine\DBAL\Driver\Result;
use Doctrine\DBAL\Driver\ResultStatement;
use Doctrine\DBAL\Driver\Statement;
use Doctrine\DBAL\FetchMode;
use InvalidArgumentException;
use IteratorAggregate;
use PDO;

use function array_map;
use function array_merge;
use function array_values;
use function assert;
use function reset;

/**
 * Cache statement for SQL results.
 *
 * A result is saved in multiple cache keys, there is the originally specified
 * cache key which is just pointing to result rows by key. The following things
 * have to be ensured:
 *
 * 1. lifetime of the original key has to be longer than that of all the individual rows keys
 * 2. if any one row key is missing the query has to be re-executed.
 *
 * Also you have to realize that the cache will load the whole result into memory at once to ensure 2.
 * This means that the memory usage for cached results might increase by using this feature.
 *
 * @deprecated
 */
class ResultCacheStatement implements IteratorAggregate, ResultStatement, Result
{
    /** @var Cache */
    private $resultCache;

    /** @var string */
    private $cacheKey;

    /** @var string */
    private $realKey;

    /** @var int */
    private $lifetime;

    /** @var ResultStatement */
    private $statement;

    /** @var array<int,array<string,mixed>>|null */
    private $data;

    /** @var int */
    private $defaultFetchMode = FetchMode::MIXED;

    /**
     * @param string $cacheKey
     * @param string $realKey
     * @param int    $lifetime
     */
    public function __construct(ResultStatement $stmt, Cache $resultCache, $cacheKey, $realKey, $lifetime)
    {
        $this->statement   = $stmt;
        $this->resultCache = $resultCache;
        $this->cacheKey    = $cacheKey;
        $this->realKey     = $realKey;
        $this->lifetime    = $lifetime;
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
    public function columnCount()
    {
        return $this->statement->columnCount();
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated Use one of the fetch- or iterate-related methods.
     */
    public function setFetchMode($fetchMode, $arg2 = null, $arg3 = null)
    {
        $this->defaultFetchMode = $fetchMode;

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated Use iterateNumeric(), iterateAssociative() or iterateColumn() instead.
     */
    public function getIterator()
    {
        $data = $this->fetchAll();

        return new ArrayIterator($data);
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated Use fetchNumeric(), fetchAssociative() or fetchOne() instead.
     */
    public function fetch($fetchMode = null, $cursorOrientation = PDO::FETCH_ORI_NEXT, $cursorOffset = 0)
    {
        if ($this->data === null) {
            $this->data = [];
        }

        $row = $this->statement->fetch(FetchMode::ASSOCIATIVE);

        if ($row) {
            $this->data[] = $row;

            $fetchMode = $fetchMode ?: $this->defaultFetchMode;

            if ($fetchMode === FetchMode::ASSOCIATIVE) {
                return $row;
            }

            if ($fetchMode === FetchMode::NUMERIC) {
                return array_values($row);
            }

            if ($fetchMode === FetchMode::MIXED) {
                return array_merge($row, array_values($row));
            }

            if ($fetchMode === FetchMode::COLUMN) {
                return reset($row);
            }

            throw new InvalidArgumentException('Invalid fetch-style given for caching result.');
        }

        $this->saveToCache();

        return false;
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated Use fetchAllNumeric(), fetchAllAssociative() or fetchFirstColumn() instead.
     */
    public function fetchAll($fetchMode = null, $fetchArgument = null, $ctorArgs = null)
    {
        $data = $this->statement->fetchAll(FetchMode::ASSOCIATIVE, $fetchArgument, $ctorArgs);

        $this->data = $data;

        $this->saveToCache();

        if ($fetchMode === FetchMode::NUMERIC) {
            foreach ($data as $i => $row) {
                $data[$i] = array_values($row);
            }
        } elseif ($fetchMode === FetchMode::MIXED) {
            foreach ($data as $i => $row) {
                $data[$i] = array_merge($row, array_values($row));
            }
        } elseif ($fetchMode === FetchMode::COLUMN) {
            foreach ($data as $i => $row) {
                $data[$i] = reset($row);
            }
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated Use fetchOne() instead.
     */
    public function fetchColumn($columnIndex = 0)
    {
        $row = $this->fetch(FetchMode::NUMERIC);

        // TODO: verify that return false is the correct behavior
        return $row[$columnIndex] ?? false;
    }

    /**
     * {@inheritdoc}
     */
    public function fetchNumeric()
    {
        $row = $this->doFetch();

        if ($row === false) {
            return false;
        }

        return array_values($row);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchAssociative()
    {
        return $this->doFetch();
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
        if ($this->statement instanceof Result) {
            $data = $this->statement->fetchAllAssociative();
        } else {
            $data = $this->statement->fetchAll(FetchMode::ASSOCIATIVE);
        }

        $this->store($data);

        return array_map('array_values', $data);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchAllAssociative(): array
    {
        if ($this->statement instanceof Result) {
            $data = $this->statement->fetchAllAssociative();
        } else {
            $data = $this->statement->fetchAll(FetchMode::ASSOCIATIVE);
        }

        $this->store($data);

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function fetchFirstColumn(): array
    {
        return FetchUtils::fetchFirstColumn($this);
    }

    /**
     * Returns the number of rows affected by the last DELETE, INSERT, or UPDATE statement
     * executed by the corresponding object.
     *
     * If the last SQL statement executed by the associated Statement object was a SELECT statement,
     * some databases may return the number of rows returned by that statement. However,
     * this behaviour is not guaranteed for all databases and should not be
     * relied on for portable applications.
     *
     * @return int The number of rows.
     */
    public function rowCount()
    {
        assert($this->statement instanceof Statement);

        return $this->statement->rowCount();
    }

    public function free(): void
    {
        $this->data = null;
    }

    /**
     * @return array<string,mixed>|false
     *
     * @throws Exception
     */
    private function doFetch()
    {
        if ($this->data === null) {
            $this->data = [];
        }

        if ($this->statement instanceof Result) {
            $row = $this->statement->fetchAssociative();
        } else {
            $row = $this->statement->fetch(FetchMode::ASSOCIATIVE);
        }

        if ($row !== false) {
            $this->data[] = $row;

            return $row;
        }

        $this->saveToCache();

        return false;
    }

    /**
     * @param array<int,array<string,mixed>> $data
     */
    private function store(array $data): void
    {
        $this->data = $data;
    }

    private function saveToCache(): void
    {
        if ($this->data === null) {
            return;
        }

        $data = $this->resultCache->fetch($this->cacheKey);
        if (! $data) {
            $data = [];
        }

        $data[$this->realKey] = $this->data;

        $this->resultCache->save($this->cacheKey, $data, $this->lifetime);
    }
}
