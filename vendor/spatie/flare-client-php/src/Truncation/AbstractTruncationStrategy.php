<?php

namespace Spatie\FlareClient\Truncation;

abstract class AbstractTruncationStrategy implements TruncationStrategy
{
    protected ReportTrimmer $reportTrimmer;

    public function __construct(ReportTrimmer $reportTrimmer)
    {
        $this->reportTrimmer = $reportTrimmer;
    }
}
