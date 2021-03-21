<?php

namespace Enlightn\Enlightn\Analyzers\Performance;

use Enlightn\Enlightn\Analyzers\Concerns\ParsesConfigurationFiles;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

class DebugLogAnalyzer extends PerformanceAnalyzer
{
    use ParsesConfigurationFiles;

    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = 'Your application does not use the debug log level in production.';

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
        return "Your app log level is set to debug while your application is in a non-local environment. "
            ."This is not recommended and may slow down your application.";
    }

    /**
     * Execute the analyzer.
     *
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @return void
     */
    public function handle(ConfigRepository $config)
    {
        if ($config->get('app.env') === 'local') {
            return;
        }

        $defaultLogChannel = $config->get('logging.default');

        if ($defaultLogChannel !== 'stack'
            && $config->get('logging.channels.'.$defaultLogChannel.'.level') === 'debug') {
            $this->recordError('logging', 'level', ['channels', $defaultLogChannel]);
        }

        if ($defaultLogChannel === 'stack') {
            foreach ($config->get('logging.channels.stack.channels') as $channel) {
                if ($config->get('logging.channels.'.$channel.'.level') === 'debug') {
                    $this->recordError('logging', 'level', ['channels', 'ignore_exceptions', $channel]);
                }
            }
        }
    }
}
