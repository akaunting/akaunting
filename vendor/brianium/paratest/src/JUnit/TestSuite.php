<?php

declare(strict_types=1);

namespace ParaTest\JUnit;

use SimpleXMLElement;
use SplFileInfo;

use function assert;
use function count;
use function file_get_contents;

/**
 * @internal
 *
 * @immutable
 */
final class TestSuite
{
    /**
     * @param array<string, TestSuite> $suites
     * @param list<TestCase>           $cases
     */
    public function __construct(
        public readonly string $name,
        public readonly int $tests,
        public readonly int $assertions,
        public readonly int $failures,
        public readonly int $errors,
        public readonly int $skipped,
        public readonly float $time,
        public readonly string $file,
        public readonly array $suites,
        public readonly array $cases
    ) {
    }

    public static function fromFile(SplFileInfo $logFile): self
    {
        assert($logFile->isFile() && 0 < (int) $logFile->getSize());

        $logFileContents = file_get_contents($logFile->getPathname());
        assert($logFileContents !== false);

        return self::parseTestSuite(
            new SimpleXMLElement($logFileContents),
            true,
        );
    }

    private static function parseTestSuite(SimpleXMLElement $node, bool $isRootSuite): self
    {
        if ($isRootSuite) {
            $tests      = 0;
            $assertions = 0;
            $failures   = 0;
            $errors     = 0;
            $skipped    = 0;
            $time       = 0;
        } else {
            $tests      = (int) $node['tests'];
            $assertions = (int) $node['assertions'];
            $failures   = (int) $node['failures'];
            $errors     = (int) $node['errors'];
            $skipped    = (int) $node['skipped'];
            $time       = (float) $node['time'];
        }

        $count  = count($node->testsuite);
        $suites = [];
        foreach ($node->testsuite as $singleTestSuiteXml) {
            $testSuite = self::parseTestSuite($singleTestSuiteXml, false);
            if ($isRootSuite && $count === 1) {
                return $testSuite;
            }

            $suites[$testSuite->name] = $testSuite;

            if (! $isRootSuite) {
                continue;
            }

            $tests      += $testSuite->tests;
            $assertions += $testSuite->assertions;
            $failures   += $testSuite->failures;
            $errors     += $testSuite->errors;
            $skipped    += $testSuite->skipped;
            $time       += $testSuite->time;
        }

        $cases = [];
        foreach ($node->testcase as $singleTestCase) {
            $cases[] = TestCase::caseFromNode($singleTestCase);
        }

        return new self(
            (string) $node['name'],
            $tests,
            $assertions,
            $failures,
            $errors,
            $skipped,
            $time,
            (string) $node['file'],
            $suites,
            $cases,
        );
    }
}
