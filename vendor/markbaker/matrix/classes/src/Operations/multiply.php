<?php

/**
 *
 * Function code for the matrix multiplication operation
 *
 * @copyright  Copyright (c) 2018 Mark Baker (https://github.com/MarkBaker/PHPMatrix)
 * @license    https://opensource.org/licenses/MIT    MIT
 */

namespace Matrix;

use Matrix\Operators\Multiplication;

/**
 * Multiplies two or more matrices
 *
 * @param array<int, mixed> $matrixValues The matrices to multiply
 * @return Matrix
 * @throws Exception
 */
function multiply(...$matrixValues): Matrix
{
    if (count($matrixValues) < 2) {
        throw new Exception('Multiplication operation requires at least 2 arguments');
    }

    $matrix = array_shift($matrixValues);

    if (is_array($matrix)) {
        $matrix = new Matrix($matrix);
    }
    if (!$matrix instanceof Matrix) {
        throw new Exception('Multiplication arguments must be Matrix or array');
    }

    $result = new Multiplication($matrix);

    foreach ($matrixValues as $matrix) {
        $result->execute($matrix);
    }

    return $result->result();
}
