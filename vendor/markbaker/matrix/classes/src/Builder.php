<?php

/**
 *
 * Class for the creating "special" Matrices
 *
 * @copyright  Copyright (c) 2018 Mark Baker (https://github.com/MarkBaker/PHPMatrix)
 * @license    https://opensource.org/licenses/MIT    MIT
 */

namespace Matrix;

/**
 * Matrix Builder class.
 *
 * @package Matrix
 */
class Builder
{
    /**
     * Create a new matrix of specified dimensions, and filled with a specified value
     * If the column argument isn't provided, then a square matrix will be created
     *
     * @param mixed $fillValue
     * @param int $rows
     * @param int|null $columns
     * @return Matrix
     * @throws Exception
     */
    public static function createFilledMatrix($fillValue, $rows, $columns = null)
    {
        if ($columns === null) {
            $columns = $rows;
        }

        $rows = Matrix::validateRow($rows);
        $columns = Matrix::validateColumn($columns);

        return new Matrix(
            array_fill(
                0,
                $rows,
                array_fill(
                    0,
                    $columns,
                    $fillValue
                )
            )
        );
    }

    /**
     * Create a new identity matrix of specified dimensions
     * This will always be a square matrix, with the number of rows and columns matching the provided dimension
     *
     * @param int $dimensions
     * @return Matrix
     * @throws Exception
     */
    public static function createIdentityMatrix($dimensions)
    {
        $grid = static::createFilledMatrix(null, $dimensions)->toArray();

        for ($x = 0; $x < $dimensions; ++$x) {
            $grid[$x][$x] = 1;
        }

        return new Matrix($grid);
    }
}
