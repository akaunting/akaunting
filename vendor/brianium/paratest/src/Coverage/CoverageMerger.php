<?php

declare(strict_types=1);

namespace ParaTest\Coverage;

use SebastianBergmann\CodeCoverage\CodeCoverage;
use SplFileInfo;

use function assert;

/** @internal */
final class CoverageMerger
{
    public function __construct(
        private readonly CodeCoverage $coverage
    ) {
    }

    public function addCoverageFromFile(SplFileInfo $coverageFile): void
    {
        if (! $coverageFile->isFile() || $coverageFile->getSize() === 0) {
            return;
        }

        /** @psalm-suppress UnresolvableInclude **/
        $coverage = include $coverageFile->getPathname();
        assert($coverage instanceof CodeCoverage);

        $this->coverage->merge($coverage);
    }
}
