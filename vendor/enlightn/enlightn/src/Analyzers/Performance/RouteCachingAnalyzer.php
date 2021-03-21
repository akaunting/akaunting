<?php

namespace Enlightn\Enlightn\Analyzers\Performance;

use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Foundation\CachesRoutes;

class RouteCachingAnalyzer extends PerformanceAnalyzer
{
    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = 'Application route caching is configured properly.';

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
     * Execute the analyzer.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @return void
     */
    public function handle(Application $app, ConfigRepository $config)
    {
        if ($config->get('app.env') === 'local' && $app->routesAreCached()) {
            $this->errorMessage = "Your app routes are cached in a local environment. "
                ."This is not recommended for development because as you change your route files, "
                ."the changes will not be reflected unless you clear the cache.";

            $this->markFailed();
        } elseif ($config->get('app.env') !== 'local' && ! $app->routesAreCached()) {
            $this->errorMessage = "Your app routes are not cached in a non-local environment. "
                ."Route caching enables a significant improvement of upto 5X and it is recommended to "
                ."enable this in production. Remember to add the Artisan route:cache command "
                ."to your deployment script so that every time you deploy, the cache is regenerated.";

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
        // Skip the analyzer if application does not implement the interface
        return class_exists(CachesRoutes::class) && ! (app() instanceof CachesRoutes);
    }
}
