<?php

namespace DebugBar\DataCollector\PDO;

use PDO;
use PDOException;
use DebugBar\DataCollector\PDO\TraceablePDOStatement;

/**
 * A PDO proxy which traces statements
 */
class TraceablePDO extends PDO
{
    /** @var PDO */
    protected $pdo;

    /** @var TracedStatement[] */
    protected $executedStatements = [];

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->pdo->setAttribute(PDO::ATTR_STATEMENT_CLASS, [TraceablePDOStatement::class, [$this]]);
    }

   /**
    * Initiates a transaction
    *
    * @link   http://php.net/manual/en/pdo.begintransaction.php
    * @return bool TRUE on success or FALSE on failure.
    */
    public function beginTransaction() : bool
    {
        return $this->pdo->beginTransaction();
    }

    /**
     * Commits a transaction
     *
     * @link   http://php.net/manual/en/pdo.commit.php
     * @return bool TRUE on success or FALSE on failure.
     */
    public function commit() : bool
    {
        return $this->pdo->commit();
    }

    /**
     * Fetch extended error information associated with the last operation on the database handle
     *
     * @link   http://php.net/manual/en/pdo.errorinfo.php
     * @return array PDO::errorInfo returns an array of error information
     */
    #[\ReturnTypeWillChange]
    public function errorCode()
    {
        return $this->pdo->errorCode();
    }

    /**
     * Fetch extended error information associated with the last operation on the database handle
     *
     * @link   http://php.net/manual/en/pdo.errorinfo.php
     * @return array PDO::errorInfo returns an array of error information
     */
    public function errorInfo() : array
    {
        return $this->pdo->errorInfo();
    }

    /**
     * Execute an SQL statement and return the number of affected rows
     *
     * @link   http://php.net/manual/en/pdo.exec.php
     * @param  string   $statement
     * @return int|bool PDO::exec returns the number of rows that were modified or deleted by the
     * SQL statement you issued. If no rows were affected, PDO::exec returns 0. This function may
     * return Boolean FALSE, but may also return a non-Boolean value which evaluates to FALSE.
     * Please read the section on Booleans for more information
     */
    #[\ReturnTypeWillChange]
    public function exec($statement)
    {
        return $this->profileCall('exec', $statement, func_get_args());
    }

    /**
     * Retrieve a database connection attribute
     *
     * @link   http://php.net/manual/en/pdo.getattribute.php
     * @param  int   $attribute One of the PDO::ATTR_* constants
     * @return mixed A successful call returns the value of the requested PDO attribute.
     * An unsuccessful call returns null.
     */
    #[\ReturnTypeWillChange]
    public function getAttribute($attribute)
    {
        return $this->pdo->getAttribute($attribute);
    }

    /**
     * Checks if inside a transaction
     *
     * @link   http://php.net/manual/en/pdo.intransaction.php
     * @return bool TRUE if a transaction is currently active, and FALSE if not.
     */
    public function inTransaction() : bool
    {
        return $this->pdo->inTransaction();
    }

    /**
     * Returns the ID of the last inserted row or sequence value
     *
     * @link   http://php.net/manual/en/pdo.lastinsertid.php
     * @param  string $name [optional]
     * @return string If a sequence name was not specified for the name parameter, PDO::lastInsertId
     * returns a string representing the row ID of the last row that was inserted into the database.
     */
    #[\ReturnTypeWillChange]
    public function lastInsertId($name = null)
    {
        return $this->pdo->lastInsertId($name);
    }

    /**
     * Prepares a statement for execution and returns a statement object
     *
     * @link   http://php.net/manual/en/pdo.prepare.php
     * @param  string $statement This must be a valid SQL statement template for the target DB server.
     * @param  array  $driver_options [optional] This array holds one or more key=&gt;value pairs to
     * set attribute values for the PDOStatement object that this method returns.
     * @return TraceablePDOStatement|bool If the database server successfully prepares the statement,
     * PDO::prepare returns a PDOStatement object. If the database server cannot successfully prepare
     * the statement, PDO::prepare returns FALSE or emits PDOException (depending on error handling).
     */
    #[\ReturnTypeWillChange]
    public function prepare($statement, $driver_options = [])
    {
        return $this->pdo->prepare($statement, $driver_options);
    }

    /**
     * Executes an SQL statement, returning a result set as a PDOStatement object
     *
     * @link   http://php.net/manual/en/pdo.query.php
     * @param  string $statement
     * @param  int $fetchMode
     * @param  mixed ...$fetchModeArgs
     * @return TraceablePDOStatement|bool PDO::query returns a PDOStatement object, or FALSE on
     * failure.
     */
    #[\ReturnTypeWillChange]
    public function query($statement, $fetchMode = null, ...$fetchModeArgs)
    {
        return $this->profileCall('query', $statement, func_get_args());
    }

    /**
     * Quotes a string for use in a query.
     *
     * @link   http://php.net/manual/en/pdo.quote.php
     * @param  string $string The string to be quoted.
     * @param  int    $parameter_type [optional] Provides a data type hint for drivers that have
     * alternate quoting styles.
     * @return string|bool A quoted string that is theoretically safe to pass into an SQL statement.
     * Returns FALSE if the driver does not support quoting in this way.
     */
    #[\ReturnTypeWillChange]
    public function quote($string, $parameter_type = PDO::PARAM_STR)
    {
        return $this->pdo->quote($string, $parameter_type);
    }

    /**
     * Rolls back a transaction
     *
     * @link   http://php.net/manual/en/pdo.rollback.php
     * @return bool TRUE on success or FALSE on failure.
     */
    public function rollBack() : bool
    {
        return $this->pdo->rollBack();
    }

    /**
     * Set an attribute
     *
     * @link   http://php.net/manual/en/pdo.setattribute.php
     * @param  int $attribute
     * @param  mixed $value
     * @return bool TRUE on success or FALSE on failure.
     */
    public function setAttribute($attribute, $value) : bool
    {
        return $this->pdo->setAttribute($attribute, $value);
    }

    /**
     * Profiles a call to a PDO method
     *
     * @param  string $method
     * @param  string $sql
     * @param  array  $args
     * @return mixed  The result of the call
     */
    #[\ReturnTypeWillChange]
    protected function profileCall($method, $sql, array $args)
    {
        $trace = new TracedStatement($sql);
        $trace->start();

        $ex = null;
        try {
            $result = $this->__call($method, $args);
        } catch (PDOException $e) {
            $ex = $e;
        }

        if ($this->pdo->getAttribute(PDO::ATTR_ERRMODE) !== PDO::ERRMODE_EXCEPTION && $result === false) {
            $error = $this->pdo->errorInfo();
            $ex = new PDOException($error[2], $error[0]);
        }

        $trace->end($ex);
        $this->addExecutedStatement($trace);

        if ($this->pdo->getAttribute(PDO::ATTR_ERRMODE) === PDO::ERRMODE_EXCEPTION && $ex !== null) {
            throw $ex;
        }
        return $result;
    }

    /**
     * Adds an executed TracedStatement
     *
     * @param TracedStatement $stmt
     */
    public function addExecutedStatement(TracedStatement $stmt) : void
    {
        $this->executedStatements[] = $stmt;
    }

    /**
     * Returns the accumulated execution time of statements
     *
     * @return float
     */
    public function getAccumulatedStatementsDuration() : float
    {
        return array_reduce($this->executedStatements, function ($v, $s) { return $v + $s->getDuration(); });
    }

    /**
     * Returns the peak memory usage while performing statements
     *
     * @return int
     */
    public function getMemoryUsage() : int
    {
        return array_reduce($this->executedStatements, function ($v, $s) { return $v + $s->getMemoryUsage(); });
    }

    /**
     * Returns the peak memory usage while performing statements
     *
     * @return int
     */
    public function getPeakMemoryUsage() : int
    {
        return array_reduce($this->executedStatements, function ($v, $s) { $m = $s->getEndMemory(); return $m > $v ? $m : $v; });
    }

    /**
     * Returns the list of executed statements as TracedStatement objects
     *
     * @return TracedStatement[]
     */
    public function getExecutedStatements() : array
    {
        return $this->executedStatements;
    }

    /**
     * Returns the list of failed statements
     *
     * @return TracedStatement[]
     */
    public function getFailedExecutedStatements() : array
    {
        return array_filter($this->executedStatements, function ($s) { return !$s->isSuccess(); });
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->pdo->$name;
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->pdo->$name = $value;
    }

    /**
     * @param $name
     * @param $args
     * @return mixed
     */
    public function __call($name, $args)
    {
        return call_user_func_array([$this->pdo, $name], $args);
    }
}
