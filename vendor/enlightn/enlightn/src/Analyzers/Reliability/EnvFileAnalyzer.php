<?php

namespace Enlightn\Enlightn\Analyzers\Reliability;

use Illuminate\Filesystem\Filesystem;

class EnvFileAnalyzer extends ReliabilityAnalyzer
{
    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = "A .env file exists for your application.";

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
        return "Your application's .env file is missing.";
    }

    /**
     * Execute the analyzer.
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     * @return void
     */
    public function handle(Filesystem $files)
    {
        if (! $files->exists(base_path('.env'))) {
            $this->markFailed();
        }
    }
}
