<?php

namespace Enlightn\Enlightn\Analyzers\Reliability;

use Illuminate\Contracts\Foundation\Application;

class MaintenanceModeAnalyzer extends ReliabilityAnalyzer
{
    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = "Your application is not currently in maintenance mode.";

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
    public $timeToFix = 1;

    /**
     * Get the error message describing the analyzer insights.
     *
     * @return string
     */
    public function errorMessage()
    {
        return "Your application is currently in maintenance mode.";
    }

    /**
     * Execute the analyzer.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @return void
     */
    public function handle(Application $app)
    {
        if ($app->isDownForMaintenance()) {
            $this->markFailed();
        }
    }
}
