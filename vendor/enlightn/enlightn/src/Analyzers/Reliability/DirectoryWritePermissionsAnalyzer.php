<?php

namespace Enlightn\Enlightn\Analyzers\Reliability;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class DirectoryWritePermissionsAnalyzer extends ReliabilityAnalyzer
{
    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = 'Your storage and cache directories are writable.';

    /**
     * The severity of the analyzer.
     *
     * @var string|null
     */
    public $severity = self::SEVERITY_CRITICAL;

    /**
     * The time to fix in minutes.
     *
     * @var int|null
     */
    public $timeToFix = 5;

    /**
     * The directories that are not writable.
     *
     * @var string
     */
    protected $failedDirs;

    /**
     * Get the error message describing the analyzer insights.
     *
     * @return string
     */
    public function errorMessage()
    {
        return "Your application's storage and cache directories are not writable. This can cause issues "
            ."with your Laravel installation. The directories that are not writable include: {$this->failedDirs}";
    }

    /**
     * Execute the analyzer.
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     * @return void
     */
    public function handle(Filesystem $files)
    {
        $directoriesToCheck = config('enlightn.writable_directories', [
            storage_path(),
            app()->bootstrapPath('cache'),
        ]);

        $this->failedDirs = collect($directoriesToCheck)->reject(function ($directory) use ($files) {
            return $files->isWritable($directory);
        })->map(function ($path) {
            return Str::contains($path, base_path())
                ? ('['.trim(Str::after($path, base_path()), '/').']') : '['.$path.']';
        })->join(', ', ' and ');

        if (! empty($this->failedDirs)) {
            $this->markFailed();
        }
    }
}
