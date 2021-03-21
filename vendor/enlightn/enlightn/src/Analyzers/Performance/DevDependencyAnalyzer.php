<?php

namespace Enlightn\Enlightn\Analyzers\Performance;

use Enlightn\Enlightn\Composer;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Str;

class DevDependencyAnalyzer extends PerformanceAnalyzer
{
    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = 'Dev dependencies are not installed in production.';

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
    public $timeToFix = 1;

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
        return "Your application's dev dependencies are installed while your application is in a non-local environment. "
                ."This may slow down your application as dev dependencies such as Ignition are known to have memory "
                ."leaks and are automatically discovered if you have package discovery enabled.";
    }

    /**
     * Execute the analyzer.
     *
     * @param \Enlightn\Enlightn\Composer $composer
     * @return void
     */
    public function handle(Composer $composer)
    {
        if (config('app.env') === 'local') {
            return;
        }

        if (Str::contains($composer->installDryRun(['--no-dev']), 'Removing')) {
            // If composer install --dry-run --no-dev results in removing a package, that means
            // that the application has installed dev dependencies.
            $this->markFailed();
        }
    }
}
