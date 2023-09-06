<?php

namespace Matrix;

class Functions
{
    /**
     * Validates an array of matrix, converting an array to a matrix if required.
     *
     * @param Matrix|array $matrix Matrix or an array to treat as a matrix.
     * @return Matrix The new matrix
     * @throws Exception If argument isn't a valid matrix or array.
     */
    private static function validateMatrix($matrix)
    {
        if (is_array($matrix)) {
            $matrix = new Matrix($matrix);
        }
        if (!$matrix instanceof Matrix) {
            throw new Exception('Must be Matrix or array');
        }

        return $matrix;
    }

    /**
     * Calculate the adjoint of the matrix
     *
     * @param Matrix $matrix The matrix whose adjoint we wish to calculate
     * @return Matrix
     *
     * @throws Exception
     */
    private static function getAdjoint(Matrix $matrix)
    {
        return self::transpose(
            self::getCofactors($matrix)
        );
    }

    /**
     * Return the adjoint of this matrix
     * The adjugate, classical adjoint, or adjunct of a square matrix is the transpose of its cofactor matrix.
     * The adjugate has sometimes been called the "adjoint", but today the "adjoint" of a matrix normally refers
     *     to its corresponding adjoint operator, which is its conjugate transpose.
     *
     * @param Matrix|array $matrix The matrix whose adjoint we wish to calculate
     * @return Matrix
     * @throws Exception
     **/
    public static function adjoint($matrix)
    {
        $matrix = self::validateMatrix($matrix);

        if (!$matrix->isSquare()) {
            throw new Exception('Adjoint can only be calculated for a square matrix');
        }

        return self::getAdjoint($matrix);
    }

    /**
     * Calculate the cofactors of the matrix
     *
     * @param Matrix $matrix The matrix whose cofactors we wish to calculate
     * @return Matrix
     *
     * @throws Exception
     */
    private static function getCofactors(Matrix $matrix)
    {
        $cofactors = self::getMinors($matrix);
        $dimensions = $matrix->rows;

        $cof = 1;
        for ($i = 0; $i < $dimensions; ++$i) {
            $cofs = $cof;
            for ($j = 0; $j < $dimensions; ++$j) {
                $cofactors[$i][$j] *= $cofs;
                $cofs = -$cofs;
            }
            $cof = -$cof;
        }

        return new Matrix($cofactors);
    }

    /**
     * Return the cofactors of this matrix
     *
     * @param Matrix|array $matrix The matrix whose cofactors we wish to calculate
     * @return Matrix
     *
     * @throws Exception
     */
    public static function cofactors($matrix)
    {
        $matrix = self::validateMatrix($matrix);

        if (!$matrix->isSquare()) {
            throw new Exception('Cofactors can only be calculated for a square matrix');
        }

        return self::getCofactors($matrix);
    }

    /**
     * @param Matrix $matrix
     * @param int $row
     * @param int $column
     * @return float
     * @throws Exception
     */
    private static function getDeterminantSegment(Matrix $matrix, $row, $column)
    {
        $tmpMatrix = $matrix->toArray();
        unset($tmpMatrix[$row]);
        array_walk(
            $tmpMatrix,
            function (&$row) use ($column) {
                unset($row[$column]);
            }
        );

        return self::getDeterminant(new Matrix($tmpMatrix));
    }

    /**
     * Calculate the determinant of the matrix
     *
     * @param Matrix $matrix The matrix whose determinant we wish to calculate
     * @return float
     *
     * @throws Exception
     */
    private static function getDeterminant(Matrix $matrix)
    {
        $dimensions = $matrix->rows;
        $determinant = 0;

        switch ($dimensions) {
            case 1:
                $determinant = $matrix->getValue(1, 1);
                break;
            case 2:
                $determinant = $matrix->getValue(1, 1) * $matrix->getValue(2, 2) -
                    $matrix->getValue(1, 2) * $matrix->getValue(2, 1);
                break;
            default:
                for ($i = 1; $i <= $dimensions; ++$i) {
                    $det = $matrix->getValue(1, $i) * self::getDeterminantSegment($matrix, 0, $i - 1);
                    if (($i % 2) == 0) {
                        $determinant -= $det;
                    } else {
                        $determinant += $det;
                    }
                }
                break;
        }

        return $determinant;
    }

    /**
     * Return the determinant of this matrix
     *
     * @param Matrix|array $matrix The matrix whose determinant we wish to calculate
     * @return float
     * @throws Exception
     **/
    public static function determinant($matrix)
    {
        $matrix = self::validateMatrix($matrix);

        if (!$matrix->isSquare()) {
            throw new Exception('Determinant can only be calculated for a square matrix');
        }

        return self::getDeterminant($matrix);
    }

    /**
     * Return the diagonal of this matrix
     *
     * @param Matrix|array $matrix The matrix whose diagonal we wish to calculate
     * @return Matrix
     * @throws Exception
     **/
    public static function diagonal($matrix)
    {
        $matrix = self::validateMatrix($matrix);

        if (!$matrix->isSquare()) {
            throw new Exception('Diagonal can only be extracted from a square matrix');
        }

        $dimensions = $matrix->rows;
        $grid = Builder::createFilledMatrix(0, $dimensions, $dimensions)
            ->toArray();

        for ($i = 0; $i < $dimensions; ++$i) {
            $grid[$i][$i] = $matrix->getValue($i + 1, $i + 1);
        }

        return new Matrix($grid);
    }

    /**
     * Return the antidiagonal of this matrix
     *
     * @param Matrix|array $matrix The matrix whose antidiagonal we wish to calculate
     * @return Matrix
     * @throws Exception
     **/
    public static function antidiagonal($matrix)
    {
        $matrix = self::validateMatrix($matrix);

        if (!$matrix->isSquare()) {
            throw new Exception('Anti-Diagonal can only be extracted from a square matrix');
        }

        $dimensions = $matrix->rows;
        $grid = Builder::createFilledMatrix(0, $dimensions, $dimensions)
            ->toArray();

        for ($i = 0; $i < $dimensions; ++$i) {
            $grid[$i][$dimensions - $i - 1] = $matrix->getValue($i + 1, $dimensions - $i);
        }

        return new Matrix($grid);
    }

    /**
     * Return the identity matrix
     * The identity matrix, or sometimes ambiguously called a unit matrix, of size n is the n × n square matrix
     *   with ones on the main diagonal and zeros elsewhere
     *
     * @param Matrix|array $matrix The matrix whose identity we wish to calculate
     * @return Matrix
     * @throws Exception
     **/
    public static function identity($matrix)
    {
        $matrix = self::validateMatrix($matrix);

        if (!$matrix->isSquare()) {
            throw new Exception('Identity can only be created for a square matrix');
        }

        $dimensions = $matrix->rows;

        return Builder::createIdentityMatrix($dimensions);
    }

    /**
     * Return the inverse of this matrix
     *
     * @param Matrix|array $matrix The matrix whose inverse we wish to calculate
     * @return Matrix
     * @throws Exception
     **/
    public static function inverse($matrix, string $type = 'inverse')
    {
        $matrix = self::validateMatrix($matrix);

        if (!$matrix->isSquare()) {
            throw new Exception(ucfirst($type) . ' can only be calculated for a square matrix');
        }

        $determinant = self::getDeterminant($matrix);
        if ($determinant == 0.0) {
            throw new Div0Exception(ucfirst($type) . ' can only be calculated for a matrix with a non-zero determinant');
        }

        if ($matrix->rows == 1) {
            return new Matrix([[1 / $matrix->getValue(1, 1)]]);
        }

        return self::getAdjoint($matrix)
            ->multiply(1 / $determinant);
    }

    /**
     * Calculate the minors of the matrix
     *
     * @param Matrix $matrix The matrix whose minors we wish to calculate
     * @return array[]
     *
     * @throws Exception
     */
    protected static function getMinors(Matrix $matrix)
    {
        $minors = $matrix->toArray();
        $dimensions = $matrix->rows;
        if ($dimensions == 1) {
            return $minors;
        }

        for ($i = 0; $i < $dimensions; ++$i) {
            for ($j = 0; $j < $dimensions; ++$j) {
                $minors[$i][$j] = self::getDeterminantSegment($matrix, $i, $j);
            }
        }

        return $minors;
    }

    /**
     * Return the minors of the matrix
     * The minor of a matrix A is the determinant of some smaller square matrix, cut down from A by removing one or
     *     more of its rows or columns.
     * Minors obtained by removing just one row and one column from square matrices (first minors) are required for
     *     calculating matrix cofactors, which in turn are useful for computing both the determinant and inverse of
     *     square matrices.
     *
     * @param Matrix|array $matrix The matrix whose minors we wish to calculate
     * @return Matrix
     * @throws Exception
     **/
    public static function minors($matrix)
    {
        $matrix = self::validateMatrix($matrix);

        if (!$matrix->isSquare()) {
            throw new Exception('Minors can only be calculated for a square matrix');
        }

        return new Matrix(self::getMinors($matrix));
    }

    /**
     * Return the trace of this matrix
     * The trace is defined as the sum of the elements on the main diagonal (the diagonal from the upper left to the lower right)
     *     of the matrix
     *
     * @param Matrix|array $matrix The matrix whose trace we wish to calculate
     * @return float
     * @throws Exception
     **/
    public static function trace($matrix)
    {
        $matrix = self::validateMatrix($matrix);

        if (!$matrix->isSquare()) {
            throw new Exception('Trace can only be extracted from a square matrix');
        }

        $dimensions = $matrix->rows;
        $result = 0;
        for ($i = 1; $i <= $dimensions; ++$i) {
            $result += $matrix->getValue($i, $i);
        }

        return $result;
    }

    /**
     * Return the transpose of this matrix
     *
     * @param Matrix|\a $matrix The matrix whose transpose we wish to calculate
     * @return Matrix
     **/
    public static function transpose($matrix)
    {
        $matrix = self::validateMatrix($matrix);

        $array = array_values(array_merge([null], $matrix->toArray()));
        $grid = call_user_func_array(
            'array_map',
            $array
        );

        return new Matrix($grid);
    }
}
