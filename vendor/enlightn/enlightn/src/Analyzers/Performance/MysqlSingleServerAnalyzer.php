<?php

namespace Enlightn\Enlightn\Analyzers\Performance;

use Enlightn\Enlightn\Analyzers\Concerns\ParsesConfigurationFiles;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

class MysqlSingleServerAnalyzer extends PerformanceAnalyzer
{
    use ParsesConfigurationFiles;

    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = 'MySQL is configured properly on single server setups.';

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
    public $timeToFix = 30;

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
        return "When MySQL is running on the same server as your app, it is recommended to use unix "
                ."sockets instead of TCP ports to improve performance by upto 50% (Percona benchmark).";
    }

    /**
     * Execute the analyzer.
     *
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @return void
     */
    public function handle(ConfigRepository $config)
    {
        $localConnections = collect($config->get('database.connections', []))
                            ->filter(function ($conf) {
                                // filter the local connections that don't use sockets
                                return isset($conf['driver']) && $conf['driver'] === 'mysql'
                                    && isset($conf['host']) && $conf['host'] === '127.0.0.1'
                                    && (! isset($conf['unix_socket']) || empty($conf['unix_socket']));
                            });

        if ($localConnections->count() > 0) {
            // On same server setups, it is recommended to use unix sockets
            $this->recordError('database', 'unix_socket');
        }
    }

    /**
     * Determine whether to skip the analyzer.
     *
     * @return bool
     */
    public function skip()
    {
        if ($this->isLocalAndShouldSkip()
            || config('database.connections.'.config('database.default').'.driver') !== 'mysql') {
            return true;
        }

        return false;
    }
}
