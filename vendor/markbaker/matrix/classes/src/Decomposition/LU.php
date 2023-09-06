<?php

namespace Matrix\Decomposition;

use Matrix\Exception;
use Matrix\Matrix;

class LU
{
    private $luMatrix;
    private $rows;
    private $columns;

    private $pivot = [];

    public function __construct(Matrix $matrix)
    {
        $this->luMatrix = $matrix->toArray();
        $this->rows = $matrix->rows;
        $this->columns = $matrix->columns;

        $this->buildPivot();
    }

    /**
     * Get lower triangular factor.
     *
     * @return Matrix Lower triangular factor
     */
    public function getL(): Matrix
    {
        $lower = [];

        $columns = min($this->rows, $this->columns);
        for ($row = 0; $row < $this->rows; ++$row) {
            for ($column = 0; $column < $columns; ++$column) {
                if ($row > $column) {
                    $lower[$row][$column] = $this->luMatrix[$row][$column];
                } elseif ($row === $column) {
                    $lower[$row][$column] = 1.0;
                } else {
                    $lower[$row][$column] = 0.0;
                }
            }
        }

        return new Matrix($lower);
    }

    /**
     * Get upper triangular factor.
     *
     * @return Matrix Upper triangular factor
     */
    public function getU(): Matrix
    {
        $upper = [];

        $rows = min($this->rows, $this->columns);
        for ($row = 0; $row < $rows; ++$row) {
            for ($column = 0; $column < $this->columns; ++$column) {
                if ($row <= $column) {
                    $upper[$row][$column] = $this->luMatrix[$row][$column];
                } else {
                    $upper[$row][$column] = 0.0;
                }
            }
        }

        return new Matrix($upper);
    }

    /**
     * Return pivot permutation vector.
     *
     * @return Matrix Pivot matrix
     */
    public function getP(): Matrix
    {
        $pMatrix = [];

        $pivots = $this->pivot;
        $pivotCount = count($pivots);
        foreach ($pivots as $row => $pivot) {
            $pMatrix[$row] = array_fill(0, $pivotCount, 0);
            $pMatrix[$row][$pivot] = 1;
        }

        return new Matrix($pMatrix);
    }

    /**
     * Return pivot permutation vector.
     *
     * @return array Pivot vector
     */
    public function getPivot(): array
    {
        return $this->pivot;
    }

    /**
     *    Is the matrix nonsingular?
     *
     * @return bool true if U, and hence A, is nonsingular
     */
    public function isNonsingular(): bool
    {
        for ($diagonal = 0; $diagonal < $this->columns; ++$diagonal) {
            if ($this->luMatrix[$diagonal][$diagonal] === 0.0) {
                return false;
            }
        }

        return true;
    }

    private function buildPivot(): void
    {
        for ($row = 0; $row < $this->rows; ++$row) {
            $this->pivot[$row] = $row;
        }

        for ($column = 0; $column < $this->columns; ++$column) {
            $luColumn = $this->localisedReferenceColumn($column);

            $this->applyTransformations($column, $luColumn);

            $pivot = $this->findPivot($column, $luColumn);
            if ($pivot !== $column) {
                $this->pivotExchange($pivot, $column);
            }

            $this->computeMultipliers($column);

            unset($luColumn);
        }
    }

    private function localisedReferenceColumn($column): array
    {
        $luColumn = [];

        for ($row = 0; $row < $this->rows; ++$row) {
            $luColumn[$row] = &$this->luMatrix[$row][$column];
        }

        return $luColumn;
    }

    private function applyTransformations($column, array $luColumn): void
    {
        for ($row = 0; $row < $this->rows; ++$row) {
            $luRow = $this->luMatrix[$row];
            // Most of the time is spent in the following dot product.
            $kmax = min($row, $column);
            $sValue = 0.0;
            for ($kValue = 0; $kValue < $kmax; ++$kValue) {
                $sValue += $luRow[$kValue] * $luColumn[$kValue];
            }
            $luRow[$column] = $luColumn[$row] -= $sValue;
        }
    }

    private function findPivot($column, array $luColumn): int
    {
        $pivot = $column;
        for ($row = $column + 1; $row < $this->rows; ++$row) {
            if (abs($luColumn[$row]) > abs($luColumn[$pivot])) {
                $pivot = $row;
            }
        }

        return $pivot;
    }

    private function pivotExchange($pivot, $column): void
    {
        for ($kValue = 0; $kValue < $this->columns; ++$kValue) {
            $tValue = $this->luMatrix[$pivot][$kValue];
            $this->luMatrix[$pivot][$kValue] = $this->luMatrix[$column][$kValue];
            $this->luMatrix[$column][$kValue] = $tValue;
        }

        $lValue = $this->pivot[$pivot];
        $this->pivot[$pivot] = $this->pivot[$column];
        $this->pivot[$column] = $lValue;
    }

    private function computeMultipliers($diagonal): void
    {
        if (($diagonal < $this->rows) && ($this->luMatrix[$diagonal][$diagonal] != 0.0)) {
            for ($row = $diagonal + 1; $row < $this->rows; ++$row) {
                $this->luMatrix[$row][$diagonal] /= $this->luMatrix[$diagonal][$diagonal];
            }
        }
    }

    private function pivotB(Matrix $B): array
    {
        $X = [];
        foreach ($this->pivot as $rowId) {
            $row = $B->getRows($rowId + 1)->toArray();
            $X[] = array_pop($row);
        }

        return $X;
    }

    /**
     * Solve A*X = B.
     *
     * @param Matrix $B a Matrix with as many rows as A and any number of columns
     *
     * @throws Exception
     *
     * @return Matrix X so that L*U*X = B(piv,:)
     */
    public function solve(Matrix $B): Matrix
    {
        if ($B->rows !== $this->rows) {
            throw new Exception('Matrix row dimensions are not equal');
        }

        if ($this->rows !== $this->columns) {
            throw new Exception('LU solve() only works on square matrices');
        }

        if (!$this->isNonsingular()) {
            throw new Exception('Can only perform operation on singular matrix');
        }

        // Copy right hand side with pivoting
        $nx = $B->columns;
        $X = $this->pivotB($B);

        // Solve L*Y = B(piv,:)
        for ($k = 0; $k < $this->columns; ++$k) {
            for ($i = $k + 1; $i < $this->columns; ++$i) {
                for ($j = 0; $j < $nx; ++$j) {
                    $X[$i][$j] -= $X[$k][$j] * $this->luMatrix[$i][$k];
                }
            }
        }

        // Solve U*X = Y;
        for ($k = $this->columns - 1; $k >= 0; --$k) {
            for ($j = 0; $j < $nx; ++$j) {
                $X[$k][$j] /= $this->luMatrix[$k][$k];
            }
            for ($i = 0; $i < $k; ++$i) {
                for ($j = 0; $j < $nx; ++$j) {
                    $X[$i][$j] -= $X[$k][$j] * $this->luMatrix[$i][$k];
                }
            }
        }

        return new Matrix($X);
    }
}
