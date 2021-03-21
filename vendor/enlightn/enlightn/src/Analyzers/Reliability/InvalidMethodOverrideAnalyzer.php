<?php

namespace Enlightn\Enlightn\Analyzers\Reliability;

use Enlightn\Enlightn\Analyzers\Concerns\ParsesPHPStanAnalysis;
use Enlightn\Enlightn\PHPStan;

class InvalidMethodOverrideAnalyzer extends ReliabilityAnalyzer
{
    use ParsesPHPStanAnalysis;

    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = "Your application does not contain invalid method overrides.";

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
    public $timeToFix = 5;

    /**
     * Get the error message describing the analyzer insights.
     *
     * @return string
     */
    public function errorMessage()
    {
        return "Your application seems to contain invalid method overrides.";
    }

    /**
     * Execute the analyzer.
     *
     * @param \Enlightn\Enlightn\PHPStan $PHPStan
     * @return void
     */
    public function handle(PHPStan $PHPStan)
    {
        $this->matchPHPStanAnalysis($PHPStan, [
            'Method * overrides final method *', 'Non-static method * overrides static method *',
            'Static method * overrides non-static method *', '* method overriding * method * should *',
            'Method * overrides method * but misses parameter *', 'Parameter * of method * is passed by reference but *',
            'Parameter * of method * is not passed by reference but *', 'Parameter * of method * is not optional*',
            'Parameter * of method * is not variadic*', 'Parameter * of method * is not contravariant*',
            'Parameter * of method * is variadic*', 'Parameter * of method * is required*',
            'Parameter * of method * does not match*', 'Parameter * of method * is not compatible*',
            'Return type * of method * is not covariant *', 'Return type * of method * is not compatible *',
        ]);
    }
}
