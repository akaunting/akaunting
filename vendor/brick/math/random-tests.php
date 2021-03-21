<?php

/**
 * This script stress tests calculators with random large numbers and ensures that all implementations return the same
 * results. It is designed to run in an infinite loop unless a bug is found.
 */

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Brick\Math\Internal\Calculator;

(new class(30) { // max digits
    private $gmp;
    private $bcmath;
    private $native;

    private $maxDigits;

    public function __construct(int $maxDigits)
    {
        $this->gmp    = new Calculator\GmpCalculator();
        $this->bcmath = new Calculator\BcMathCalculator();
        $this->native = new Calculator\NativeCalculator();

        $this->maxDigits = $maxDigits;
    }

    public function __invoke() : void
    {
        for (;;) {
            $a = $this->generateRandomNumber();
            $b = $this->generateRandomNumber();
            $c = $this->generateRandomNumber();

            $this->runTests($a, $b);
            $this->runTests($b, $a);

            if ($a !== '0') {
                $this->runTests("-$a", $b);
                $this->runTests($b, "-$a");
            }

            if ($b !== '0') {
                $this->runTests($a, "-$b");
                $this->runTests("-$b", $a);
            }

            if ($a !== '0' && $b !== '0') {
                $this->runTests("-$a", "-$b");
                $this->runTests("-$b", "-$a");
            }

            if ($c !== '0') {
                $this->test("$a POW $b MOD $c", function(Calculator $calc) use($a, $b, $c) {
                    return $calc->modPow($a, $b, $c);
                });
            }
        }
    }

    /**
     * @param string $a The left operand.
     * @param string $b The right operand.
     */
    private function runTests(string $a, string $b) : void
    {
        $this->test("$a + $b", function(Calculator $c) use($a, $b) {
            return $c->add($a, $b);
        });

        $this->test("$a - $b", function(Calculator $c) use($a, $b) {
            return $c->sub($a, $b);
        });

        $this->test("$a * $b", function(Calculator $c) use($a, $b) {
            return $c->mul($a, $b);
        });

        if ($b !== '0') {
            $this->test("$a / $b", function(Calculator $c) use($a, $b) {
                return $c->divQR($a, $b);
            });

            $this->test("$a MOD $b", function(Calculator $c) use($a, $b) {
                return $c->mod($a, $b);
            });
        }

        if ($b !== '0' && $b[0] !== '-') {
            $this->test("INV $a MOD $b", function(Calculator $c) use($a, $b) {
                return $c->modInverse($a, $b);
            });
        }

        $this->test("GCD $a, $b", function(Calculator $c) use($a, $b) {
            return $c->gcd($a, $b);
        });

        if ($a[0] !== '-') {
            $this->test("SQRT $a", function(Calculator $c) use($a, $b) {
                return $c->sqrt($a);
            });
        }

        $this->test("$a AND $b", function(Calculator $c) use($a, $b) {
            return $c->and($a, $b);
        });

        $this->test("$a OR $b", function(Calculator $c) use($a, $b) {
            return $c->or($a, $b);
        });

        $this->test("$a XOR $b", function(Calculator $c) use($a, $b) {
            return $c->xor($a, $b);
        });
    }

    /**
     * @param string  $test     A string representing the test being executed.
     * @param Closure $callback A callback function accepting a Calculator instance and returning a calculation result.
     */
    private function test(string $test, Closure $callback) : void
    {
        static $testCounter = 0;
        static $lastOutputTime = 0.0;
        static $currentSecond = 0;
        static $currentSecondTestCounter = 0;
        static $testsPerSecond = 0;

        $gmpResult    = $callback($this->gmp);
        $bcmathResult = $callback($this->bcmath);
        $nativeResult = $callback($this->native);

        if ($gmpResult !== $bcmathResult) {
            self::failure('GMP', 'BCMath', $test);
        }

        if ($gmpResult !== $nativeResult) {
            self::failure('GMP', 'Native', $test);
        }

        $testCounter++;
        $currentSecondTestCounter++;

        $time = microtime(true);
        $second = (int) $time;

        if ($second !== $currentSecond) {
            $currentSecond = $second;
            $testsPerSecond = $currentSecondTestCounter;
            $currentSecondTestCounter = 0;
        }

        if ($time - $lastOutputTime >= 0.1) {
            echo "\r", number_format($testCounter), ' (', number_format($testsPerSecond) . ' / s)';
            $lastOutputTime = $time;
        }
    }

    /**
     * @param string $c1   The name of the first calculator.
     * @param string $c2   The name of the second calculator.
     * @param string $test A string representing the test being executed.
     */
    private static function failure(string $c1, string $c2, string $test) : void
    {
        echo PHP_EOL;
        echo 'FAILURE!', PHP_EOL;
        echo $c1, ' vs ', $c2, PHP_EOL;
        echo $test, PHP_EOL;
        die;
    }

    private function generateRandomNumber() : string
    {
        $length = random_int(1, $this->maxDigits);

        $number = '';

        for ($i = 0; $i < $length; $i++) {
            $number .= random_int(0, 9);
        }

        $number = ltrim($number, '0');

        if ($number === '') {
            return '0';
        }

        return $number;
    }
})();
