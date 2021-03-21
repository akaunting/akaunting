<?php

namespace Enlightn\Enlightn\Analyzers\Reliability;

use Enlightn\Enlightn\Analyzers\Analyzer;

abstract class ReliabilityAnalyzer extends Analyzer
{
    /**
     * The category of the analyzer.
     *
     * @var string|null
     */
    public $category = 'Reliability';
}
