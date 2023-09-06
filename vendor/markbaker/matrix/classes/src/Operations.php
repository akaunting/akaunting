<?php

namespace Matrix;

use Matrix\Operators\Addition;
use Matrix\Operators\DirectSum;
use Matrix\Operators\Division;
use Matrix\Operators\Multiplication;
use Matrix\Operators\Subtraction;

class Operations
{
    public static function add(...$matrixValues): Matrix
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

    public static function directsum(...$matrixValues): Matrix
    {
        if (count($matrixValues) < 2) {
            throw new Exception('DirectSum operation requires at least 2 arguments');
        }

        $matrix = array_shift($matrixValues);

        if (is_array($matrix)) {
            $matrix = new Matrix($matrix);
        }
        if (!$matrix instanceof Matrix) {
            throw new Exception('DirectSum arguments must be Matrix or array');
        }

        $result = new DirectSum($matrix);

        foreach ($matrixValues as $matrix) {
            $result->execute($matrix);
        }

        return $result->result();
    }

    public static function divideby(...$matrixValues): Matrix
    {
        if (count($matrixValues) < 2) {
            throw new Exception('Division operation requires at least 2 arguments');
        }

        $matrix = array_shift($matrixValues);

        if (is_array($matrix)) {
            $matrix = new Matrix($matrix);
        }
        if (!$matrix instanceof Matrix) {
            throw new Exception('Division arguments must be Matrix or array');
        }

        $result = new Division($matrix);

        foreach ($matrixValues as $matrix) {
            $result->execute($matrix);
        }

        return $result->result();
    }

    public static function divideinto(...$matrixValues): Matrix
    {
        if (count($matrixValues) < 2) {
            throw new Exception('Division operation requires at least 2 arguments');
        }

        $matrix = array_pop($matrixValues);
        $matrixValues = array_reverse($matrixValues);

        if (is_array($matrix)) {
            $matrix = new Matrix($matrix);
        }
        if (!$matrix instanceof Matrix) {
            throw new Exception('Division arguments must be Matrix or array');
        }

        $result = new Division($matrix);

        foreach ($matrixValues as $matrix) {
            $result->execute($matrix);
        }

        return $result->result();
    }

    public static function multiply(...$matrixValues): Matrix
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

    public static function subtract(...$matrixValues): Matrix
    {
        if (count($matrixValues) < 2) {
            throw new Exception('Subtraction operation requires at least 2 arguments');
        }

        $matrix = array_shift($matrixValues);

        if (is_array($matrix)) {
            $matrix = new Matrix($matrix);
        }
        if (!$matrix instanceof Matrix) {
            throw new Exception('Subtraction arguments must be Matrix or array');
        }

        $result = new Subtraction($matrix);

        foreach ($matrixValues as $matrix) {
            $result->execute($matrix);
        }

        return $result->result();
    }
}
