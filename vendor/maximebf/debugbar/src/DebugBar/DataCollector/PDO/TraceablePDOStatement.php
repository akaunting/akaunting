<?php

namespace DebugBar\DataCollector\PDO;

use PDO;
use PDOException;
use PDOStatement;

/**
 * A traceable PDO statement to use with Traceablepdo
 */
class TraceablePDOStatement extends PDOStatement
{
    /** @var PDO */
    protected $pdo;

    /** @var array */
    protected $boundParameters = [];

    /**
     * TraceablePDOStatement constructor.
     *
     * @param TraceablePDO $pdo
     */
    protected function __construct(TraceablePDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Bind a column to a PHP variable
     *
     * @link   http://php.net/manual/en/pdostatement.bindcolumn.php
     * @param  mixed $column Number of the column (1-indexed) or name of the column in the result set
     * @param  mixed $param  Name of the PHP variable to which the column will be bound.
     * @param  int   $type [optional] Data type of the parameter, specified by the PDO::PARAM_*
     * constants.
     * @param  int   $maxlen [optional] A hint for pre-allocation.
     * @param  mixed $driverdata [optional] Optional parameter(s) for the driver.
     * @return bool  TRUE on success or FALSE on failure.
     */
    #[\ReturnTypeWillChange]
    public function bindColumn($column, &$param, $type = null, $maxlen = null, $driverdata = null)
    {
        $this->boundParameters[$column] = $param;
        $args = array_merge([$column, &$param], array_slice(func_get_args(), 2));
        return call_user_func_array(['parent', 'bindColumn'], $args);
    }

    /**
     * Binds a parameter to the specified variable name
     *
     * @link   http://php.net/manual/en/pdostatement.bindparam.php
     * @param  mixed $parameter Parameter identifier. For a prepared statement using named
     * placeholders, this will be a parameter name of the form :name. For a prepared statement using
     * question mark placeholders, this will be the 1-indexed position of the parameter.
     * @param  mixed $variable  Name of the PHP variable to bind to the SQL statement parameter.
     * @param  int $data_type [optional] Explicit data type for the parameter using the PDO::PARAM_*
     * constants.
     * @param  int $length [optional] Length of the data type. To indicate that a parameter is an OUT
     * parameter from a stored procedure, you must explicitly set the length.
     * @param  mixed $driver_options [optional]
     * @return bool TRUE on success or FALSE on failure.
     */
    public function bindParam($parameter, &$variable, $data_type = PDO::PARAM_STR, $length = null, $driver_options = null) : bool
    {
        $this->boundParameters[$parameter] = $variable;
        $args = array_merge([$parameter, &$variable], array_slice(func_get_args(), 2));
        return call_user_func_array(['parent', 'bindParam'], $args);
    }

    /**
     * Binds a value to a parameter
     *
     * @link   http://php.net/manual/en/pdostatement.bindvalue.php
     * @param  mixed $parameter Parameter identifier. For a prepared statement using named
     * placeholders, this will be a parameter name of the form :name. For a prepared statement using
     * question mark placeholders, this will be the 1-indexed position of the parameter.
     * @param  mixed $value The value to bind to the parameter.
     * @param  int   $data_type [optional] Explicit data type for the parameter using the PDO::PARAM_*
     * constants.
     * @return bool TRUE on success or FALSE on failure.
     */
    public function bindValue($parameter, $value, $data_type = PDO::PARAM_STR) : bool
    {
        $this->boundParameters[$parameter] = $value;
        return call_user_func_array(['parent', 'bindValue'], func_get_args());
    }

    /**
     * Executes a prepared statement
     *
     * @link   http://php.net/manual/en/pdostatement.execute.php
     * @param  array $input_parameters [optional] An array of values with as many elements as there
     * are bound parameters in the SQL statement being executed. All values are treated as
     * PDO::PARAM_STR.
     * @throws PDOException
     * @return bool TRUE on success or FALSE on failure.
     */
    public function execute($input_parameters = null) : bool
    {
        $preparedId = spl_object_hash($this);
        $boundParameters = $this->boundParameters;
        if (is_array($input_parameters)) {
            $boundParameters = array_merge($boundParameters, $input_parameters);
        }

        $trace = new TracedStatement($this->queryString, $boundParameters, $preparedId);
        $trace->start();

        $ex = null;
        try {
            $result = parent::execute($input_parameters);
        } catch (PDOException $e) {
            $ex = $e;
        }

        if ($this->pdo->getAttribute(PDO::ATTR_ERRMODE) !== PDO::ERRMODE_EXCEPTION && $result === false) {
            $error = $this->errorInfo();
            $ex = new PDOException($error[2], (int) $error[0]);
        }

        $trace->end($ex, $this->rowCount());
        $this->pdo->addExecutedStatement($trace);

        if ($this->pdo->getAttribute(PDO::ATTR_ERRMODE) === PDO::ERRMODE_EXCEPTION && $ex !== null) {
            throw $ex;
        }
        return $result;
    }
}
