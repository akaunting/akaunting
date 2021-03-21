<?php

/**
 *
 * Function code for the matrix addition operation
 *
 * @copyright  Copyright (c) 2018 Mark Baker (https://github.com/MarkBaker/PHPMatrix)
 * @license    https://opensource.org/licenses/MIT    MIT
 */

namespace Matrix;

use Matrix\Operators\Addition;

/**
 * Adds two or more matrices
 *
 * @param array<int, mixed> $matrixValues The matrices to add
 * @return Matrix
 * @throws Exception
 */
function add(...$matrixValues): Matrix
{
    if (count($matrixValues) < 2) {
        throw new Exception('Addition operation requires at least 2 arguments');
    }

    $matrix = array_shift($matrixValues);

    if (is_array($matrix)) {
        $matrix = new Matrix($matrix);
    }
    if (!$matrix instanceof Matrix) {
        throw new Exception('Addition arguments must be Matrix or array');
    }

    $result = new Addition($matrix);

    foreach ($matrixValues as $matrix) {
        $result->execute($matrix);
    }

    return $result->result();
}
