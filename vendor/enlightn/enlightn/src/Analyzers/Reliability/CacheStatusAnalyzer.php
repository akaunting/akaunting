<?php

namespace Enlightn\Enlightn\Analyzers\Reliability;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Throwable;

class CacheStatusAnalyzer extends ReliabilityAnalyzer
{
    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = "Your application cache is working.";

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
     * Determine whether the analyzer should be run in CI mode.
     *
     * @var bool
     */
    public static $runInCI = false;

    /**
     * The current disk usage (in percentage).
     *
     * @var float
     */
    protected $diskUsage;

    /**
     * Get the error message describing the analyzer insights.
     *
     * @return string
     */
    public function errorMessage()
    {
        return "Your application cache seems to be offline.";
    }

    /**
     * Execute the analyzer.
     *
     * @return void
     */
    public function handle()
    {
        $payload = Str::random(10);

        try {
            Cache::put('enlightn:check', $payload, 10);

            if (Cache::get('enlightn:check') !== $payload) {
                $this->markFailed();
            }
        } catch (Throwable $e) {
            $this->markFailed();
        }
    }
}
