<?php

namespace Enlightn\Enlightn\Console\Formatters;

use Illuminate\Console\Command;

interface Formatter
{
    /**
     * Called before analysis for initialization or printing a greeting message.
     *
     * @param \Illuminate\Console\Command $command
     * @return void
     */
    public function beforeAnalysis(Command $command);

    /**
     * Called for each analyzer with the result.
     *
     * @param \Illuminate\Console\Command $command
     * @param array $result
     * @param int $current
     * @param int $total
     * @param bool $allAnalyzers
     * @return void
     */
    public function parseAnalyzerResult(Command $command, array $result, int $current, int $total, bool $allAnalyzers);

    /**
     * Called after analysis for printing the final output.
     *
     * @param \Illuminate\Console\Command $command
     * @param bool $allAnalyzers
     * @return void
     */
    public function afterAnalysis(Command $command, bool $allAnalyzers);
}
