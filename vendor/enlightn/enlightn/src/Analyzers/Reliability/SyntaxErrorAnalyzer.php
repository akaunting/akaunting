<?php

namespace Enlightn\Enlightn\Analyzers\Reliability;

use Enlightn\Enlightn\Inspection\Inspector;

class SyntaxErrorAnalyzer extends ReliabilityAnalyzer
{
    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = 'There are no syntax errors in your application code.';

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
    public $timeToFix = 5;

    /**
     * Get the error message describing the analyzer insights.
     *
     * @return string
     */
    public function errorMessage()
    {
        return "Your application has some PHP files with syntax errors.";
    }

    /**
     * Execute the analyzer.
     *
     * @param \Enlightn\Enlightn\Inspection\Inspector $inspector
     * @return void
     */
    public function handle(Inspector $inspector)
    {
        if (count($inspector->errors) > 0) {
            $this->markFailed();

            foreach ($inspector->errors as $path => $lineNumbers) {
                foreach ($lineNumbers as $lineNumber) {
                    $this->addTrace($path, $lineNumber);
                }
            }
        }
    }
}
