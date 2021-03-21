<?php

namespace Enlightn\Enlightn\Analyzers\Reliability;

use Enlightn\Enlightn\Composer;
use Illuminate\Support\Str;

class ComposerValidationAnalyzer extends ReliabilityAnalyzer
{
    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = "Your application's composer.json file is valid.";

    /**
     * The severity of the analyzer.
     *
     * @var string|null
     */
    public $severity = self::SEVERITY_CRITICAL;

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
        return "Your application's composer.json file is not valid. Run the composer validate command "
            ."to view more details.";
    }

    /**
     * Execute the analyzer.
     *
     * @param Composer $composer
     * @return void
     */
    public function handle(Composer $composer)
    {
        if (! Str::contains($composer->runCommand(['validate']), 'is valid')) {
            $this->markFailed();
        }
    }
}
