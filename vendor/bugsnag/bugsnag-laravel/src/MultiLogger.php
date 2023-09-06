<?php

namespace Bugsnag\BugsnagLaravel;

use Bugsnag\PsrLogger\MultiLogger as BaseLogger;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Logging\Log;

class MultiLogger extends BaseLogger implements Log
{
    use EventTrait;

    /**
     * Create a new multi logger instance.
     *
     * @param \Psr\Log\LoggerInterface[]                   $loggers
     * @param \Illuminate\Contracts\Events\Dispatcher|null $dispatcher
     *
     * @return void
     */
    public function __construct(array $loggers, Dispatcher $dispatcher = null)
    {
        parent::__construct($loggers);

        $this->dispatcher = $dispatcher;
    }

    /**
     * Register a file log handler.
     *
     * @param string $path
     * @param string $level
     *
     * @return void
     */
    public function useFiles($path, $level = 'debug')
    {
        foreach ($this->loggers as $logger) {
            if ($logger instanceof Log) {
                $logger->useFiles($path, $level);
            }
        }
    }

    /**
     * Register a daily file log handler.
     *
     * @param string $path
     * @param int    $days
     * @param string $level
     *
     * @return void
     */
    public function useDailyFiles($path, $days = 0, $level = 'debug')
    {
        foreach ($this->loggers as $logger) {
            if ($logger instanceof Log) {
                $logger->useDailyFiles($path, $days, $level);
            }
        }
    }

    /**
     * Get the underlying Monolog instance.
     *
     * @return \Monolog\Logger
     */
    public function getMonolog()
    {
        foreach ($this->loggers as $logger) {
            if (is_callable([$logger, 'getMonolog'])) {
                $monolog = $logger->getMonolog();

                if ($monolog === null) {
                    continue;
                }

                return $monolog;
            }
        }
    }
}
