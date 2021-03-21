<?php

namespace Enlightn\Enlightn\Analyzers\Security;

use Enlightn\Enlightn\Analyzers\Concerns\ParsesConfigurationFiles;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

class AppDebugHideAnalyzer extends SecurityAnalyzer
{
    use ParsesConfigurationFiles;

    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = 'Sensitive environment variables are hidden in non-local environments.';

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
     * Get the error message describing the analyzer insights.
     *
     * @return string
     */
    public function errorMessage()
    {
        return "You haven't set any variables to hide in debug mode while your application seems to be in a non-local "
                ."environment and set to debug mode. This can be very dangerous as users will be able to view detailed "
                ."error messages along with sensitive environment variables.";
    }

    /**
     * Execute the analyzer.
     *
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @return void
     */
    public function handle(ConfigRepository $config)
    {
        if ($config->get('app.debug') &&
            $config->get('app.env') !== 'local' &&
            empty($config->get('app.debug_blacklist', $config->get('app.debug_hide', [])))) {
            $this->recordError('app', 'debug');
        }
    }

    /**
     * Determine whether to skip the analyzer.
     *
     * @return bool
     */
    public function skip()
    {
        return ! class_exists(\Whoops\Handler\Handler::class);
    }
}
