<?php

declare(strict_types=1);

namespace ParaTest\JUnit;

use SplFileInfo;

use function array_merge;
use function assert;
use function ksort;

/**
 * @internal
 *
 * @immutable
 */
final class LogMerger
{
    /** @param list<SplFileInfo> $junitFiles */
    public function merge(array $junitFiles): TestSuite
    {
        $mainSuite = null;
        foreach ($junitFiles as $junitFile) {
            if (! $junitFile->isFile()) {
                continue;
            }

            $otherSuite = TestSuite::fromFile($junitFile);
            if ($mainSuite === null) {
                $mainSuite = $otherSuite;
                continue;
            }

            if ($mainSuite->name !== $otherSuite->name) {
                if ($mainSuite->name !== '') {
                    $mainSuite = new TestSuite(
                        '',
                        $mainSuite->tests,
                        $mainSuite->assertions,
                        $mainSuite->failures,
                        $mainSuite->errors,
                        $mainSuite->skipped,
                        $mainSuite->time,
                        '',
                        [$mainSuite->name => $mainSuite],
                        [],
                    );
                }

                if ($otherSuite->name !== '') {
                    $otherSuite = new TestSuite(
                        '',
                        $otherSuite->tests,
                        $otherSuite->assertions,
                        $otherSuite->failures,
                        $otherSuite->errors,
                        $otherSuite->skipped,
                        $otherSuite->time,
                        '',
                        [$otherSuite->name => $otherSuite],
                        [],
                    );
                }
            }

            $mainSuite = $this->mergeSuites($mainSuite, $otherSuite);
        }

        assert($mainSuite !== null);

        return $mainSuite;
    }

    private function mergeSuites(TestSuite $suite1, TestSuite $suite2): TestSuite
    {
        assert($suite1->name === $suite2->name);

        $suites = $suite1->suites;
        foreach ($suite2->suites as $suite2suiteName => $suite2suite) {
            if (! isset($suites[$suite2suiteName])) {
                $suites[$suite2suiteName] = $suite2suite;
                continue;
            }

            $suites[$suite2suiteName] = $this->mergeSuites(
                $suites[$suite2suiteName],
                $suite2suite,
            );
        }

        ksort($suites);

        return new TestSuite(
            $suite1->name,
            $suite1->tests + $suite2->tests,
            $suite1->assertions + $suite2->assertions,
            $suite1->failures + $suite2->failures,
            $suite1->errors + $suite2->errors,
            $suite1->skipped + $suite2->skipped,
            $suite1->time + $suite2->time,
            $suite1->file,
            $suites,
            array_merge($suite1->cases, $suite2->cases),
        );
    }
}
