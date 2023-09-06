<?php

namespace Matrix\Operators;

use Matrix\Matrix;
use Matrix\Exception;

abstract class Operator
{
    /**
     * Stored internally as a 2-dimension array of values
     *
     * @property mixed[][] $matrix
     **/
    protected $matrix;

    /**
     * Number of rows in the matrix
     *
     * @property integer $rows
     **/
    protected $rows;

    /**
     * Number of columns in the matrix
     *
     * @property integer $columns
     **/
    protected $columns;

    /**
     * Create an new handler object for the operation
     *
     * @param Matrix $matrix The base Matrix object on which the operation will be performed
     */
    public function __construct(Matrix $matrix)
    {
        $this->rows = $matrix->rows;
        $this->columns = $matrix->columns;
        $this->matrix = $matrix->toArray();
    }

    /**
     * Compare the dimensions of the matrices being operated on to see if they are valid for addition/subtraction
     *
     * @param Matrix $matrix The second Matrix object on which the operation will be performed
     * @throws Exception
     */
    protected function validateMatchingDimensions(Matrix $matrix): void
    {
        if (($this->rows != $matrix->rows) || ($this->columns != $matrix->columns)) {
            throw new Exception('Matrices have mismatched dimensions');
        }
    }

    /**
     * Compare the dimensions of the matrices being operated on to see if they are valid for multiplication/division
     *
     * @param Matrix $matrix The second Matrix object on which the operation will be performed
     * @throws Exception
     */
    protected function validateReflectingDimensions(Matrix $matrix): void
    {
        if ($this->columns != $matrix->rows) {
            throw new Exception('Matrices have mismatched dimensions');
        }
    }

    /**
     * Return the result of the operation
     *
     * @return Matrix
     */
    public function result(): Matrix
    {
        return new Matrix($this->matrix);
    }
}
