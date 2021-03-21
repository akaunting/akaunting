<?php

namespace Enlightn\Enlightn\Analyzers\Reliability;

use Enlightn\Enlightn\Analyzers\Concerns\ParsesConfigurationFiles;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

class QueueTimeoutAnalyzer extends ReliabilityAnalyzer
{
    use ParsesConfigurationFiles;

    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = 'An appropriate timeout and retry after is set for queues.';

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
    public $timeToFix = 2;

    /**
     * The name of the queue connection that has an error.
     *
     * @var string
     */
    public $connectionName = null;

    /**
     * The retry after queue configuration value.
     *
     * @var int
     */
    public $retryAfter = 60;

    /**
     * The timeout queue configuration value.
     *
     * @var int
     */
    public $timeout = 60;

    /**
     * Get the error message describing the analyzer insights.
     *
     * @return string
     */
    public function errorMessage()
    {
        return "The queue timeout value must be at least several seconds shorter than the retry after "
                ."configuration value. Your {$this->connectionName} queue connection's retry after value "
                ."is set at {$this->retryAfter} seconds while your timeout value is set at {$this->timeout} "
                ."seconds. This can cause problems such as your jobs may be processed twice or the queue "
                ."worker may crash.";
    }

    /**
     * Execute the analyzer.
     *
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @return void
     */
    public function handle(ConfigRepository $config)
    {
        $connections = collect($config->get('queue.connections', []))
            ->filter(function ($conf, $queue) {
                // skip sqs and sync drivers as they don't have retry after values
                return ! in_array($conf['driver'], ['sqs', 'sync']);
            })->map(function ($conf, $queue) {
                return $this->getTimeoutAndRetryAfter($conf);
            })->filter(function ($conf) {
                return $conf['timeout'] >= $conf['retry_after'];
            });

        if ($connections->count() > 0) {
            $faultyConnection = $connections->first();

            $this->connectionName = $connections->keys()->first();
            $this->timeout = $faultyConnection['timeout'];
            $this->retryAfter = $faultyConnection['retry_after'];

            $this->recordError('queue', 'retry_after', ['connections', $this->connectionName]);
        }
    }

    /**
     * Get the timeout and retry after values for the configuration.
     *
     * @param  array  $config
     * @return array
     */
    public function getTimeoutAndRetryAfter(array $config)
    {
        if (($config['driver'] ?? null) !== 'redis') {
            return ['timeout' => 60, 'retry_after' => ($config['retry_after'] ?? 60)];
        }

        // Timeout is as defined in the horizon config (if app uses Horizon) with fallback to default
        // queue worker timeout of 60 seconds.
        $timeout = array_reduce(array_merge(
            data_get(config('horizon.defaults', []), '*.timeout'),
            data_get(config('horizon.environments', []), '*.*.timeout')
        ), 'max') ?? 60;

        return ['timeout' => $timeout, 'retry_after' => ($config['retry_after'] ?? 60)];
    }
}
