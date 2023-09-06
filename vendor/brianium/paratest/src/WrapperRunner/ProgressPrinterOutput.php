<?php

declare(strict_types=1);

namespace ParaTest\WrapperRunner;

use PHPUnit\TextUI\Output\Printer;

use function preg_match;

/** @internal */
final class ProgressPrinterOutput implements Printer
{
    public function __construct(
        private readonly Printer $progressPrinter,
        private readonly Printer $outputPrinter,
    ) {
    }

    public function print(string $buffer): void
    {
        // Skip anything in \PHPUnit\TextUI\Output\Default\ProgressPrinter\ProgressPrinter::printProgress except $progress
        if (
            $buffer === "\n"
            || preg_match('/^ +$/', $buffer) === 1
            || preg_match('/^ \\d+ \\/ \\d+ \\(...%\\)$/', $buffer) === 1
        ) {
            return;
        }

        match ($buffer) {
            'E', 'F', 'I', 'N', 'D', 'R', 'W', 'S', '.' => $this->progressPrinter->print($buffer),
            default => $this->outputPrinter->print($buffer),
        };
    }

    public function flush(): void
    {
        $this->progressPrinter->flush();
        $this->outputPrinter->flush();
    }
}
