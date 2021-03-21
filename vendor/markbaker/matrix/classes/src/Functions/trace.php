<?php

/**
 *
 * Function code for the matrix trace() function
 *
 * @copyright  Copyright (c) 2018 Mark Baker (https://github.com/MarkBaker/PHPMatrix)
 * @license    https://opensource.org/licenses/MIT    MIT
 */

namespace Matrix;

/**
 * Returns the trace of a matrix or an array.
 *
 * @param Matrix|array $matrix Matrix or an array to treat as a matrix.
 * @return float The trace of the matrix
 * @throws Exception If argument isn't a valid matrix or array.
 */
function trace($matrix): float
{
    if (is_array($matrix)) {
        $matrix = new Matrix($matrix);
    }
    if (!$matrix instanceof Matrix) {
        throw new Exception('Must be Matrix or array');
    }

    return Functions::trace($matrix);
}
