<?php

/**
 *
 * Class for the management of Matrices
 *
 * @copyright  Copyright (c) 2018 Mark Baker (https://github.com/MarkBaker/PHPMatrix)
 * @license    https://opensource.org/licenses/MIT    MIT
 */

namespace Matrix;

use Generator;
use Matrix\Decomposition\LU;
use Matrix\Decomposition\QR;

/**
 * Matrix object.
 *
 * @package Matrix
 *
 * @property-read int $rows The number of rows in the matrix
 * @property-read int $columns The number of columns in the matrix
 * @method Matrix antidiagonal()
 * @method Matrix adjoint()
 * @method Matrix cofactors()
 * @method float determinant()
 * @method Matrix diagonal()
 * @method Matrix identity()
 * @method Matrix inverse()
 * @method Matrix pseudoInverse()
 * @method Matrix minors()
 * @method float trace()
 * @method Matrix transpose()
 * @method Matrix add(...$matrices)
 * @method Matrix subtract(...$matrices)
 * @method Matrix multiply(...$matrices)
 * @method Matrix divideby(...$matrices)
 * @method Matrix divideinto(...$matrices)
 * @method Matrix directsum(...$matrices)
 */
class Matrix
{
    protected $rows;
    protected $columns;
    protected $grid = [];

    /*
     * Create a new Matrix object from an array of values
     *
     * @param array $grid
     */
    final public function __construct(array $grid)
    {
        $this->buildFromArray(array_values($grid));
    }

    /*
     * Create a new Matrix object from an array of values
     *
     * @param array $grid
     */
    protected function buildFromArray(array $grid): void
    {
        $this->rows = count($grid);
        $columns = array_reduce(
            $grid,
            function ($carry, $value) {
                return max($carry, is_array($value) ? count($value) : 1);
            }
        );
        $this->columns = $columns;

        array_walk(
            $grid,
            function (&$value) use ($columns) {
                if (!is_array($value)) {
                    $value = [$value];
                }
                $value = array_pad(array_values($value), $columns, null);
            }
        );

        $this->grid = $grid;
    }

    /**
     * Validate that a row number is a positive integer
     *
     * @param int $row
     * @return int
     * @throws Exception
     */
    public static function validateRow(int $row): int
    {
        if ((!is_numeric($row)) || (intval($row) < 1)) {
            throw new Exception('Invalid Row');
        }

        return (int)$row;
    }

    /**
     * Validate that a column number is a positive integer
     *
     * @param int $column
     * @return int
     * @throws Exception
     */
    public static function validateColumn(int $column): int
    {
        if ((!is_numeric($column)) || (intval($column) < 1)) {
            throw new Exception('Invalid Column');
        }

        return (int)$column;
    }

    /**
     * Validate that a row number falls within the set of rows for this matrix
     *
     * @param int $row
     * @return int
     * @throws Exception
     */
    protected function validateRowInRange(int $row): int
    {
        $row = static::validateRow($row);
        if ($row > $this->rows) {
            throw new Exception('Requested Row exceeds matrix size');
        }

        return $row;
    }

    /**
     * Validate that a column number falls within the set of columns for this matrix
     *
     * @param int $column
     * @return int
     * @throws Exception
     */
    protected function validateColumnInRange(int $column): int
    {
        $column = static::validateColumn($column);
        if ($column > $this->columns) {
            throw new Exception('Requested Column exceeds matrix size');
        }

        return $column;
    }

    /**
     * Return a new matrix as a subset of rows from this matrix, starting at row number $row, and $rowCount rows
     * A $rowCount value of 0 will return all rows of the matrix from $row
     * A negative $rowCount value will return rows until that many rows from the end of the matrix
     *
     * Note that row numbers start from 1, not from 0
     *
     * @param int $row
     * @param int $rowCount
     * @return static
     * @throws Exception
     */
    public function getRows(int $row, int $rowCount = 1): Matrix
    {
        $row = $this->validateRowInRange($row);
        if ($rowCount === 0) {
            $rowCount = $this->rows - $row + 1;
        }

        return new static(array_slice($this->grid, $row - 1, (int)$rowCount));
    }

    /**
     * Return a new matrix as a subset of columns from this matrix, starting at column number $column, and $columnCount columns
     * A $columnCount value of 0 will return all columns of the matrix from $column
     * A negative $columnCount value will return columns until that many columns from the end of the matrix
     *
     * Note that column numbers start from 1, not from 0
     *
     * @param int $column
     * @param int $columnCount
     * @return Matrix
     * @throws Exception
     */
    public function getColumns(int $column, int $columnCount = 1): Matrix
    {
        $column = $this->validateColumnInRange($column);
        if ($columnCount < 1) {
            $columnCount = $this->columns + $columnCount - $column + 1;
        }

        $grid = [];
        for ($i = $column - 1; $i < $column + $columnCount - 1; ++$i) {
            $grid[] = array_column($this->grid, $i);
        }

        return (new static($grid))->transpose();
    }

    /**
     * Return a new matrix as a subset of rows from this matrix, dropping rows starting at row number $row,
     *     and $rowCount rows
     * A negative $rowCount value will drop rows until that many rows from the end of the matrix
     * A $rowCount value of 0 will remove all rows of the matrix from $row
     *
     * Note that row numbers start from 1, not from 0
     *
     * @param int $row
     * @param int $rowCount
     * @return static
     * @throws Exception
     */
    public function dropRows(int $row, int $rowCount = 1): Matrix
    {
        $this->validateRowInRange($row);
        if ($rowCount === 0) {
            $rowCount = $this->rows - $row + 1;
        }

        $grid = $this->grid;
        array_splice($grid, $row - 1, (int)$rowCount);

        return new static($grid);
    }

    /**
     * Return a new matrix as a subset of columns from this matrix, dropping columns starting at column number $column,
     *     and $columnCount columns
     * A negative $columnCount value will drop columns until that many columns from the end of the matrix
     * A $columnCount value of 0 will remove all columns of the matrix from $column
     *
     * Note that column numbers start from 1, not from 0
     *
     * @param int $column
     * @param int $columnCount
     * @return static
     * @throws Exception
     */
    public function dropColumns(int $column, int $columnCount = 1): Matrix
    {
        $this->validateColumnInRange($column);
        if ($columnCount < 1) {
            $columnCount = $this->columns + $columnCount - $column + 1;
        }

        $grid = $this->grid;
        array_walk(
            $grid,
            function (&$row) use ($column, $columnCount) {
                array_splice($row, $column - 1, (int)$columnCount);
            }
        );

        return new static($grid);
    }

    /**
     * Return a value from this matrix, from the "cell" identified by the row and column numbers
     * Note that row and column numbers start from 1, not from 0
     *
     * @param int $row
     * @param int $column
     * @return mixed
     * @throws Exception
     */
    public function getValue(int $row, int $column)
    {
        $row = $this->validateRowInRange($row);
        $column = $this->validateColumnInRange($column);

        return $this->grid[$row - 1][$column - 1];
    }

    /**
     * Returns a Generator that will yield each row of the matrix in turn as a vector matrix
     *     or the value of each cell if the matrix is a column vector
     *
     * @return Generator|Matrix[]|mixed[]
     */
    public function rows(): Generator
    {
        foreach ($this->grid as $i => $row) {
            yield $i + 1 => ($this->columns == 1)
                ? $row[0]
                : new static([$row]);
        }
    }

    /**
     * Returns a Generator that will yield each column of the matrix in turn as a vector matrix
     *     or the value of each cell if the matrix is a row vector
     *
     * @return Generator|Matrix[]|mixed[]
     */
    public function columns(): Generator
    {
        for ($i = 0; $i < $this->columns; ++$i) {
            yield $i + 1 => ($this->rows == 1)
                ? $this->grid[0][$i]
                : new static(array_column($this->grid, $i));
        }
    }

    /**
     * Identify if the row and column dimensions of this matrix are equal,
     *     i.e. if it is a "square" matrix
     *
     * @return bool
     */
    public function isSquare(): bool
    {
        return $this->rows === $this->columns;
    }

    /**
     * Identify if this matrix is a vector
     *     i.e. if it comprises only a single row or a single column
     *
     * @return bool
     */
    public function isVector(): bool
    {
        return $this->rows === 1 || $this->columns === 1;
    }

    /**
     * Return the matrix as a 2-dimensional array
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->grid;
    }

    /**
     * Solve A*X = B.
     *
     * @param Matrix $B Right hand side
     *
     * @throws Exception
     *
     * @return Matrix ... Solution if A is square, least squares solution otherwise
     */
    public function solve(Matrix $B): Matrix
    {
        if ($this->columns === $this->rows) {
            return (new LU($this))->solve($B);
        }

        return (new QR($this))->solve($B);
    }

    protected static $getters = [
        'rows',
        'columns',
    ];

    /**
     * Access specific properties as read-only (no setters)
     *
     * @param string $propertyName
     * @return mixed
     * @throws Exception
     */
    public function __get(string $propertyName)
    {
        $propertyName = strtolower($propertyName);

        // Test for function calls
        if (in_array($propertyName, self::$getters)) {
            return $this->$propertyName;
        }

        throw new Exception('Property does not exist');
    }

    protected static $functions = [
        'adjoint',
        'antidiagonal',
        'cofactors',
        'determinant',
        'diagonal',
        'identity',
        'inverse',
        'minors',
        'trace',
        'transpose',
    ];

    protected static $operations = [
        'add',
        'subtract',
        'multiply',
        'divideby',
        'divideinto',
        'directsum',
    ];

    /**
     * Returns the result of the function call or operation
     *
     * @param string $functionName
     * @param mixed[] $arguments
     * @return Matrix|float
     * @throws Exception
     */
    public function __call(string $functionName, $arguments)
    {
        $functionName = strtolower(str_replace('_', '', $functionName));

        if (in_array($functionName, self::$functions, true) || in_array($functionName, self::$operations, true)) {
            $functionName = "\\" . __NAMESPACE__ . "\\{$functionName}";
            if (is_callable($functionName)) {
                $arguments = array_values(array_merge([$this], $arguments));
                return call_user_func_array($functionName, $arguments);
            }
        }
        throw new Exception('Function or Operation does not exist');
    }
}
