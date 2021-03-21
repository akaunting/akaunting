<?php

namespace Enlightn\Enlightn\Analyzers\Reliability;

use Illuminate\Support\Facades\Artisan;

class UpToDateMigrationsAnalyzer extends ReliabilityAnalyzer
{
    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = "Migrations are up-to date.";

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
     * Get the error message describing the analyzer insights.
     *
     * @return string
     */
    public function errorMessage()
    {
        return "Your migrations are not up-to date. Run php artisan migrate to execute the missing migrations.";
    }

    /**
     * Execute the analyzer.
     *
     * @return void
     */
    public function handle()
    {
        Artisan::call('migrate', ['--pretend' => 'true', '--force' => 'true']);

        if (strstr(Artisan::output(), 'Nothing to migrate.') === false) {
            $this->markFailed();
        }
    }
}
