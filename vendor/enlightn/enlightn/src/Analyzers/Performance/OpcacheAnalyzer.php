<?php

namespace Enlightn\Enlightn\Analyzers\Performance;

class OpcacheAnalyzer extends PerformanceAnalyzer
{
    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = 'OPcache is enabled.';

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
     * Determine whether the analyzer should be run in CI mode.
     *
     * @var bool
     */
    public static $runInCI = false;

    /**
     * Get the error message describing the analyzer insights.
     *
     * @return string
     */
    public function errorMessage()
    {
        return "OPcache is currently disabled. OPcache can give your application a significant performance boost "
            ."and it is recommended to enable it in production.";
    }

    /**
     * Execute the analyzer.
     *
     * @return void
     */
    public function handle()
    {
        if (function_exists('opcache_get_configuration')
            && opcache_get_configuration()['directives']['opcache.enable'] ?? false) {
            return;
        }

        $this->markFailed();
    }
}
