<?php

namespace Enlightn\Enlightn\Analyzers\Performance;

use Enlightn\Enlightn\Analyzers\Concerns\AnalyzesHeaders;
use GuzzleHttp\Client;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class CacheHeaderAnalyzer extends PerformanceAnalyzer
{
    use AnalyzesHeaders;

    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = 'Your application caches compiled assets for improved performance.';

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
    public $timeToFix = 15;

    /**
     * Determine whether the analyzer should be run in CI mode.
     *
     * @var bool
     */
    public static $runInCI = false;

    /**
     * The list of uncached assets.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $unCachedAssets;

    /**
     * Create a new analyzer instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Get the error message describing the analyzer insights.
     *
     * @return string
     */
    public function errorMessage()
    {
        return "Your application does not set appropriate cache headers on your compiled Laravel Mix assets. "
            ."To improve performance, it is recommended to set Cache Control headers on your Mix assets via "
            ."your web server configuration. Your uncached assets include: {$this->formatUncachedAssets()}.";
    }

    /**
     * Execute the analyzer.
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     * @return void
     * @throws \Exception
     */
    public function handle(Filesystem $files)
    {
        $manifest = json_decode($files->get(public_path('mix-manifest.json')), true);

        $this->unCachedAssets = collect();

        foreach ($manifest as $key => $value) {
            if (is_string($value) && Str::contains($value, '?id=')
                && ! $this->headerExistsOnUrl((string) mix($key), 'Cache-Control')
                && ! $this->headerExistsOnUrl(asset($key), 'Cache-Control')) {
                // We only take the cache busted (versioned) files as the others are presumably un-cacheable.
                $this->unCachedAssets->push($key);
            }
        }

        if ($this->unCachedAssets->count() > 0) {
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
        // Skip the analyzer if it's a local env or if the application does not use Laravel Mix.
        return $this->isLocalAndShouldSkip() || ! file_exists(public_path('mix-manifest.json'));
    }

    /**
     * @return string
     */
    protected function formatUncachedAssets()
    {
        return $this->unCachedAssets->map(function ($file) {
            return "[{$file}]";
        })->join(', ', ' and ');
    }
}
