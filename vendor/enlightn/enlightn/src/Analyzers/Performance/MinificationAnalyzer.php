<?php

namespace Enlightn\Enlightn\Analyzers\Performance;

use Enlightn\Enlightn\Filesystem;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;

class MinificationAnalyzer extends PerformanceAnalyzer
{
    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = 'Your application minifies assets in production.';

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
     * A list of un-minified assets served by the application.
     *
     * @var string
     */
    protected $unMinifiedAssets;

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
        return "Your application does not minify all assets (js, css) while in a non-local environment. "
            ."Minification of assets can provide a significant performance boost for your application "
            ."and is recommended for production. Your un-minified assets include: {$this->unMinifiedAssets}.";
    }

    /**
     * Execute the analyzer.
     *
     * @param \Enlightn\Enlightn\Filesystem $files
     * @return void
     */
    public function handle(Filesystem $files)
    {
        if (config('app.env') === 'local') {
            return;
        }

        $this->unMinifiedAssets = collect($this->getFilesThatShouldBeMinified())->map(function ($fileInfo) {
            return $fileInfo->getRealPath();
        })->filter(function ($path) use ($files) {
            // We assume here that any file with more than 10 lines is not minified. That should
            // take care of the copyright notice (if any), sourcemap URL, etc. Case in point:
            // Bootstrap minified css/js actually have 7 lines.
            return $files->lines($path)->count() > 10;
        })->map(function ($path) {
            return Str::contains($path, base_path())
                ? ('['.trim(Str::after($path, base_path()), '/').']') : '['.$path.']';
        })->join(', ', ' and ');

        if (! empty($this->unMinifiedAssets)) {
            $this->markFailed();
        }
    }

    /**
     * @return \Symfony\Component\Finder\Finder
     */
    protected function getFilesThatShouldBeMinified()
    {
        // We assume that all assets are in the public directory. However, this can be configured
        // using the "build_path" configuration option.
        return (new Finder)->in(config('enlightn.build_path', public_path()))->name([
            // This would automatically include files named like *.min.js as well.
            '*.js', '*.css',
        ])->files();
    }

    /**
     * Determine whether to skip the analyzer.
     *
     * @return bool
     */
    public function skip()
    {
        // Skip the analyzer if there are no files to be minified (e.g. API only apps).
        return ($this->getFilesThatShouldBeMinified()->count() == 0);
    }
}
