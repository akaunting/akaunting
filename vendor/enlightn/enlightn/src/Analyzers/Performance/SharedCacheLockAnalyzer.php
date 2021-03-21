<?php

namespace Enlightn\Enlightn\Analyzers\Performance;

use Enlightn\Enlightn\Analyzers\Concerns\InspectsCode;
use Enlightn\Enlightn\Inspection\Inspector;
use Enlightn\Enlightn\Inspection\QueryBuilder;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Cache;

class SharedCacheLockAnalyzer extends PerformanceAnalyzer
{
    use InspectsCode;

    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = 'Your application does not use locks on your default cache store.';

    /**
     * The severity of the analyzer.
     *
     * @var string|null
     */
    public $severity = self::SEVERITY_MINOR;

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
        return 'Your application uses cache locks on your default cache store. This means that when '
            .'your cache is cleared, your locks will also be cleared. Typically, this is not the '
            .'intention when using locks for managing race conditions or concurrent processing. '
            .'If you intend to persist locks despite cache clearing, it is recommended that '
            .'you use cache locks on a separate store, which uses a connection and database that '
            .'is not shared with your default cache store.';
    }

    /**
     * Execute the analyzer.
     *
     * @param \Enlightn\Enlightn\Inspection\Inspector $inspector
     * @return void
     */
    public function handle(Inspector $inspector)
    {
        if (! is_null($lockConnection = config('cache.stores.'.config('cache.default').'.lock_connection'))
            && $lockConnection !== config('cache.stores.'.config('cache.default').'.connection')) {
            // Laravel 8.20+ ships with an option to have a separate lock connection.
            return;
        }

        $builder = (new QueryBuilder)->doesntHaveStaticCall(Cache::class, 'lock');

        $this->inspectCode($inspector, $builder);
    }

    /**
     * Determine whether to skip the analyzer.
     *
     * @return bool
     */
    public function skip()
    {
        // Skip this analyzer if the application does not use a Redis cache driver.
        return config('cache.stores.'.config('cache.default').'.driver') !== 'redis';
    }
}
