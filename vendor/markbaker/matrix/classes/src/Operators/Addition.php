<?php

namespace Matrix\Operators;

use Matrix\Matrix;
use Matrix\Exception;

class Addition extends Operator
{
    /**
     * Execute the addition
     *
     * @param mixed $value The matrix or numeric value to add to the current base value
     * @throws Exception If the provided argument is not appropriate for the operation
     * @return $this The operation object, allowing multiple additions to be chained
     **/
    public function execute($value): Operator
    {
        if (is_array($value)) {
            $value = new Matrix($value);
        }

        if (is_object($value) && ($value instanceof Matrix)) {
            return $this->addMatrix($value);
        } elseif (is_numeric($value)) {
            return $this->addScalar($value);
        }

        throw new Exception('Invalid argument for addition');
    }

    /**
     * Execute the addition for a scalar
     *
     * @param mixed $value The numeric value to add to the current base value
     * @return $this The operation object, allowing multiple additions to be chained
     **/
    protected function addScalar($value): Operator
    {
        for ($row = 0; $row < $this->rows; ++$row) {
            for ($column = 0; $column < $this->columns; ++$column) {
                $this->matrix[$row][$column] += $value;
            }
        }

        return $this;
    }

    /**
     * Execute the addition for a matrix
     *
     * @param Matrix $value The numeric value to add to the current base value
     * @return $this The operation object, allowing multiple additions to be chained
     * @throws Exception If the provided argument is not appropriate for the operation
     **/
    protected function addMatrix(Matrix $value): Operator
    {
        $this->validateMatchingDimensions($value);

        for ($row = 0; $row < $this->rows; ++$row) {
            for ($column = 0; $column < $this->columns; ++$column) {
                $this->matrix[$row][$column] += $value->getValue($row + 1, $column + 1);
            }
        }

        return $this;
    }
}
