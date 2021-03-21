<?php

namespace Enlightn\Enlightn\Analyzers\Performance;

use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Foundation\CachesConfiguration;

class ConfigCachingAnalyzer extends PerformanceAnalyzer
{
    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = 'Application config caching is configured properly.';

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
     * Determine whether the analyzer should be run in CI mode.
     *
     * @var bool
     */
    public static $runInCI = false;

    /**
     * Execute the analyzer.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @return void
     */
    public function handle(Application $app, ConfigRepository $config)
    {
        if ($config->get('app.env') === 'local' && $app->configurationIsCached()) {
            $this->errorMessage = "Your app config is cached in a local environment. "
                ."This is not recommended for development because as you change your config files, "
                ."the changes will not be reflected unless you clear the cache.";

            $this->markFailed();
        } elseif ($config->get('app.env') !== 'local' && ! $app->configurationIsCached()) {
            $this->errorMessage = "Your app config is not cached in a non-local environment. "
                ."Config caching enables a performance improvement and it is recommended to "
                ."enable this in production.";

            $this->markFailed();
        }
    }

    /**
     * Determine whether to skip the analyzer.
     *
     * @return bool
     */
    public function skip()
    {
        // Skip the analyzer if application does not implement the interface. Since the interface
        // was only introduced in Laravel 7, we will have to check if the interface class exists.
        return class_exists(CachesConfiguration::class) && ! (app() instanceof CachesConfiguration);
    }
}
