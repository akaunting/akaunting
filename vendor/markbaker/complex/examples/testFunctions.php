<?php

namespace Complex;

include(__DIR__ . '/../vendor/autoload.php');

echo 'Function Examples', PHP_EOL;

$functions = array(
    'abs',
    'acos',
    'acosh',
    'acsc',
    'acsch',
    'argument',
    'asec',
    'asech',
    'asin',
    'asinh',
    'conjugate',
    'cos',
    'cosh',
    'csc',
    'csch',
    'exp',
    'inverse',
    'ln',
    'log2',
    'log10',
    'rho',
    'sec',
    'sech',
    'sin',
    'sinh',
    'sqrt',
    'theta'
);

for ($real = -3.5; $real <= 3.5; $real += 0.5) {
    for ($imaginary = -3.5; $imaginary <= 3.5; $imaginary += 0.5) {
        foreach ($functions as $function) {
            $complexFunction = __NAMESPACE__ . '\\Functions::' . $function;
            $complex = new Complex($real, $imaginary);
            try {
                echo $function, '(', $complex, ') = ', $complexFunction($complex), PHP_EOL;
            } catch (\Exception $e) {
                echo $function, '(', $complex, ') ERROR: ', $e->getMessage(), PHP_EOL;
            }
        }
        echo PHP_EOL;
    }
}
