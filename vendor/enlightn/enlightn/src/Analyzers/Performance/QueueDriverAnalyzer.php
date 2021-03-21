<?php

namespace Enlightn\Enlightn\Analyzers\Performance;

use Enlightn\Enlightn\Analyzers\Concerns\ParsesConfigurationFiles;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

class QueueDriverAnalyzer extends PerformanceAnalyzer
{
    use ParsesConfigurationFiles;

    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = 'A proper queue driver is configured.';

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
    public $timeToFix = 60;

    /**
     * Execute the analyzer.
     *
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @return void
     */
    public function handle(ConfigRepository $config)
    {
        $connection = $config->get('queue.default');
        $driver = ucfirst($config->get("queue.connections.{$connection}.driver", "null"));

        if (method_exists($this, "assess{$driver}Driver")) {
            if (! $this->{"assess{$driver}Driver"}($config)) {
                $this->recordError('queue', 'default');
            }
        }
    }

    /**
     * Assess whether a proper queue driver is set.
     *
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @return bool
     */
    protected function assessNullDriver($config)
    {
        $this->errorMessage = "Your queue driver is set to null. This means that your app ignores "
            ."any jobs, mails, notifications or events sent to the queue. This can be very dangerous "
            ."and is only suitable for test environments in specific situations.";

        return false;
    }

    /**
     * Assess whether a proper queue driver is set.
     *
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @return bool
     */
    protected function assessSyncDriver($config)
    {
        $this->errorMessage = "Your queue driver is set to sync. This means that all jobs, mails, "
            ."notifications and event listeners will be processed immediately in a synchronous "
            ."manner. These time consuming tasks will slow down web requests and this driver is not "
            ."suitable for production environments. Even for local development, it is recommended to use "
            ."other drivers in order to accurately simulate production behaviour.";

        return false;
    }

    /**
     * Assess whether a proper queue driver is set.
     *
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @return bool
     */
    protected function assessDatabaseDriver($config)
    {
        if ($config->get('app.env') === 'local') {
            // Database queue driver is perfectly fine for local dev
            return true;
        }

        $this->errorMessage = "Your queue driver is set to database in a non-local environment. "
            ."The database queue driver is not suitable for production environments and is known "
            ."to have issues such as deadlocks and slowing down your database during peak queue "
            ."backlogs. It is strongly recommended to shift to Redis, SQS or Beanstalkd.";

        $this->severity = self::SEVERITY_MINOR;

        return false;
    }
}
