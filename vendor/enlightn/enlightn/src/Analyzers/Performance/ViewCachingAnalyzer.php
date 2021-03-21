<?php

namespace Enlightn\Enlightn\Analyzers\Performance;

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class ViewCachingAnalyzer extends PerformanceAnalyzer
{
    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = 'View caching is configured properly.';

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
     * @param \Illuminate\Filesystem\Filesystem $files
     * @return void
     */
    public function handle(Filesystem $files)
    {
        if (config('app.env') === 'local') {
            return;
        }

        $viewCount = 0;

        $this->paths()->each(function ($path) use (&$viewCount) {
            $viewCount += ($this->bladeFilesIn([$path]))->count();
        });

        $path = config('view.compiled');
        $compiledViewCount = count($files->glob("{$path}/*"));

        if ($viewCount > $compiledViewCount) {
            $this->errorMessage = "Your views are not cached in a non-local environment. "
                ."View caching enables a performance improvement and it is recommended to "
                ."enable this in production.";

            $this->markFailed();
        }
    }

    /**
     * Get the Blade files in the given path.
     *
     * @param  array  $paths
     * @return \Illuminate\Support\Collection
     */
    protected function bladeFilesIn(array $paths)
    {
        return collect(
            Finder::create()
                ->in($paths)
                ->exclude('vendor')
                ->name('*.blade.php')
                ->files()
        );
    }

    /**
     * Get all of the possible view paths.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function paths()
    {
        $finder = app('view')->getFinder();

        return collect($finder->getPaths())->merge(
            collect($finder->getHints())->flatten()
        );
    }
}
