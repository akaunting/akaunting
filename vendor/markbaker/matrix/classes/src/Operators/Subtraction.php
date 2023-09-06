<?php

namespace Matrix\Operators;

use Matrix\Matrix;
use Matrix\Exception;

class Subtraction extends Operator
{
    /**
     * Execute the subtraction
     *
     * @param mixed $value The matrix or numeric value to subtract from the current base value
     * @throws Exception If the provided argument is not appropriate for the operation
     * @return $this The operation object, allowing multiple subtractions to be chained
     **/
    public function execute($value): Operator
    {
        if (is_array($value)) {
            $value = new Matrix($value);
        }

        if (is_object($value) && ($value instanceof Matrix)) {
            return $this->subtractMatrix($value);
        } elseif (is_numeric($value)) {
            return $this->subtractScalar($value);
        }

        throw new Exception('Invalid argument for subtraction');
    }

    /**
     * Execute the subtraction for a scalar
     *
     * @param mixed $value The numeric value to subtracted from the current base value
     * @return $this The operation object, allowing multiple additions to be chained
     **/
    protected function subtractScalar($value): Operator
    {
        for ($row = 0; $row < $this->rows; ++$row) {
            for ($column = 0; $column < $this->columns; ++$column) {
                $this->matrix[$row][$column] -= $value;
            }
        }

        return $this;
    }

    /**
     * Execute the subtraction for a matrix
     *
     * @param Matrix $value The numeric value to subtract from the current base value
     * @return $this The operation object, allowing multiple subtractions to be chained
     * @throws Exception If the provided argument is not appropriate for the operation
     **/
    protected function subtractMatrix(Matrix $value): Operator
    {
        $this->validateMatchingDimensions($value);

        for ($row = 0; $row < $this->rows; ++$row) {
            for ($column = 0; $column < $this->columns; ++$column) {
                $this->matrix[$row][$column] -= $value->getValue($row + 1, $column + 1);
            }
        }

        return $this;
    }
}
