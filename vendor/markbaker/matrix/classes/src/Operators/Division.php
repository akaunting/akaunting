<?php

namespace Matrix\Operators;

use \Matrix\Matrix;
use \Matrix\Functions;
use Matrix\Exception;

class Division extends Multiplication
{
    /**
     * Execute the division
     *
     * @param mixed $value The matrix or numeric value to divide the current base value by
     * @throws Exception If the provided argument is not appropriate for the operation
     * @return $this The operation object, allowing multiple divisions to be chained
     **/
    public function execute($value): Operator
    {
        if (is_array($value)) {
            $value = new Matrix($value);
        }

        if (is_object($value) && ($value instanceof Matrix)) {
            try {
                $value = Functions::inverse($value);
            } catch (Exception $e) {
                throw new Exception('Division can only be calculated using a matrix with a non-zero determinant');
            }

            return $this->multiplyMatrix($value);
        } elseif (is_numeric($value)) {
            return $this->multiplyScalar(1 / $value);
        }

        throw new Exception('Invalid argument for division');
    }
}
