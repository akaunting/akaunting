<?php

namespace Enlightn\Enlightn\Analyzers\Performance;

use Enlightn\Enlightn\Analyzers\Concerns\ParsesPHPStanAnalysis;
use Enlightn\Enlightn\PHPStan;

class CollectionCallAnalyzer extends PerformanceAnalyzer
{
    use ParsesPHPStanAnalysis;

    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = 'Aggregation is done at the database query level rather than at the Laravel Collection level.';

    /**
     * The severity of the analyzer.
     *
     * @var string|null
     */
    public $severity = self::SEVERITY_MAJOR;

    /**
     * The time to fix in minutes.
     *
     * @var int|null
     */
    public $timeToFix = 10;

    /**
     * Get the error message describing the analyzer insights.
     *
     * @return string
     */
    public function errorMessage()
    {
        return "Your application performs some aggregations at the Laravel Collection level instead of the database "
            ."query level. For example, a `Model::all()->count()` call can easily be replaced with a `Model::count()`. "
            ."Aggregations on collections result in heavy database queries and unneeded Collection loops. This should "
            ."be avoided for better application performance.";
    }

    /**
     * Execute the analyzer.
     *
     * @param \Enlightn\Enlightn\PHPStan $phpStan
     * @return void
     */
    public function handle(PHPStan $phpStan)
    {
        $this->parsePHPStanAnalysis($phpStan, 'but could have been retrieved as a query');
    }
}
