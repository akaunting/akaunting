<?php

namespace Enlightn\Enlightn\Analyzers\Concerns;

use Enlightn\Enlightn\Analyzers\Trace;
use Enlightn\Enlightn\PHPStan;

trait ParsesPHPStanAnalysis
{
    /**
     * Parse the analysis and add traces for the errors.
     *
     * @param \Enlightn\Enlightn\PHPStan $phpStan
     * @param string|array $search
     */
    protected function parsePHPStanAnalysis(PHPStan $phpStan, $search)
    {
        collect($phpStan->parseAnalysis($search))->each(function (Trace $trace) {
            $this->addTrace($trace->path, $trace->lineNumber, $trace->details);
        });
    }

    /**
     * Parse the analysis and add traces for the errors.
     *
     * @param \Enlightn\Enlightn\PHPStan $phpStan
     * @param string|array $pattern
     */
    protected function matchPHPStanAnalysis(PHPStan $phpStan, $pattern)
    {
        collect($phpStan->match($pattern))->each(function (Trace $trace) {
            $this->addTrace($trace->path, $trace->lineNumber, $trace->details);
        });
    }
}
