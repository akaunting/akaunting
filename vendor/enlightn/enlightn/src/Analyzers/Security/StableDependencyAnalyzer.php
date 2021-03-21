<?php

namespace Enlightn\Enlightn\Analyzers\Security;

use Enlightn\Enlightn\Composer;
use Illuminate\Support\Str;

class StableDependencyAnalyzer extends SecurityAnalyzer
{
    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = 'Your application uses stable versions of dependencies.';

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
    public $timeToFix = 1;

    /**
     * Get the error message describing the analyzer insights.
     *
     * @return string
     */
    public function errorMessage()
    {
        return "Your application's dependencies are unstable versions. These may include bug fixes and/or security "
                ."patches. It is recommended to update to the most stable versions.";
    }

    /**
     * Execute the analyzer.
     *
     * @param \Enlightn\Enlightn\Composer $composer
     * @return void
     */
    public function handle(Composer $composer)
    {
        if (Str::contains($composer->updateDryRun(['--prefer-stable']), ['Upgrading', 'Downgrading'])) {
            $this->markFailed();
        }
    }
}
