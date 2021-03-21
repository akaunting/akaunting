<?php

namespace Enlightn\Enlightn\Analyzers\Security;

use Illuminate\Support\Str;

class FilePermissionsAnalyzer extends SecurityAnalyzer
{
    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = 'Your project files and directories use safe permissions.';

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
    public $timeToFix = 60;

    /**
     * @var string
     */
    protected $unsafeFilesOrDirs;

    /**
     * Get the error message describing the analyzer insights.
     *
     * @return string
     */
    public function errorMessage()
    {
        return "Your application's project directory permissions are not setup in a secure manner. This may "
            ."expose your application to be compromised if another account on the same server is vulnerable. "
            ."This can be even more dangerous if you used shared hosting. All project directories in Laravel "
            ."should be setup with a max of 775 permissions and most app files should be provided 664 (except "
            ."executables such as Artisan or your deployment scripts which should be provided 775 permissions). "
            ."These are the max level of permissions in order to be secure. Your unsafe files or directories "
            ."include: {$this->unsafeFilesOrDirs}.";
    }

    /**
     * Execute the analyzer.
     *
     * @return void
     */
    public function handle()
    {
        $filesOrDirectoriesToCheck = config('enlightn.allowed_permissions', [
            base_path() => '775',
            app_path() => '775',
            resource_path() => '775',
            storage_path() => '775',
            public_path() => '775',
            config_path() => '775',
            database_path() => '775',
            base_path('routes') => '775',
            app()->bootstrapPath() => '775',
            app()->bootstrapPath('cache') => '775',
            app()->bootstrapPath('app.php') => '664',
            base_path('artisan') => '775',
            public_path('index.php') => '664',
            public_path('server.php') => '664',
        ]);

        $this->unsafeFilesOrDirs = collect($filesOrDirectoriesToCheck)->filter(function ($allowedPermission, $path) {
            return file_exists($path) && ($allowedPermission < decoct(fileperms($path) & 0777));
        })->keys()->map(function ($path) {
            return Str::contains($path, base_path())
                ? ('['.trim(Str::after($path, base_path()), '/').']') : '['.$path.']';
        })->join(', ', ' and ');

        if (! empty($this->unsafeFilesOrDirs)) {
            $this->markFailed();
        }
    }
}
