<?php

namespace Enlightn\Enlightn\Analyzers\Security;

class PHPIniAnalyzer extends SecurityAnalyzer
{
    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = 'Your PHP configuration is secure.';

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
     * A collection of insecure PHP ini settings.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $insecureSettings;

    /**
     * Get the error message describing the analyzer insights.
     *
     * @return string
     */
    public function errorMessage()
    {
        return "Your application does not set secure php.ini configuration values. This may expose your application "
            ."to security vulnerabilities. Unless there is a very specific use case for your application, it is "
            ."recommended to set your php.ini configuration as follows: {$this->formatRecommendations()}.";
    }

    /**
     * Execute the analyzer.
     *
     * @return void
     */
    public function handle()
    {
        $secureSettings = config('enlightn.php_secure_settings', [
            'allow_url_fopen' => false,
            'allow_url_include' => false,
            'expose_php' => false,
            'display_errors' => false,
            'display_startup_errors' => false,
            'log_errors' => true,
            'ignore_repeated_errors' => false,
        ]);

        $this->insecureSettings = collect($secureSettings)->filter(function ($expected, $var) {
            return ! in_array(strtolower(ini_get($var)), $expected ? ['1', 'on', 'yes', 'true'] : ['0', 'off', '', 'no', 'false']);
        });

        if ($this->insecureSettings->count() > 0) {
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
        // Skip this analyzer if the app environment is local.
        return $this->isLocalAndShouldSkip();
    }

    /**
     * @return string
     */
    protected function formatRecommendations()
    {
        return $this->insecureSettings->map(function ($result, $var) {
            return "[{$var}: ".($result ? 'On or 1' : 'Off or 0')."]";
        })->join(', ', ' and ');
    }
}
