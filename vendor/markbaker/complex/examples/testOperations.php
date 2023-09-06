<?php

use Complex\Complex as Complex;
use Complex\Operations;

include(__DIR__ . '/../vendor/autoload.php');

$values = [
    new Complex(123),
    new Complex(456, 123),
    new Complex(0.0, 456),
];

foreach ($values as $value) {
    echo $value, PHP_EOL;
}

echo 'Addition', PHP_EOL;

$result = Operations::add(...$values);
echo '=> ', $result, PHP_EOL;

echo PHP_EOL;

echo 'Subtraction', PHP_EOL;

$result = Operations::subtract(...$values);
echo '=> ', $result, PHP_EOL;

echo PHP_EOL;

echo 'Multiplication', PHP_EOL;

$result = Operations::multiply(...$values);
echo '=> ', $result, PHP_EOL;
