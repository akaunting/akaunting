<?php

namespace Bugsnag\PsrLogger;

use Psr\Log\AbstractLogger;

class MultiLogger extends AbstractLogger
{
    /**
     * The registered loggers.
     *
     * @var \Psr\Log\LoggerInterface[]
     */
    protected array $loggers;

    /**
     * Create a new multi logger instance.
     *
     * @param \Psr\Log\LoggerInterface[] $loggers
     *
     * @return void
     */
    public function __construct(array $loggers)
    {
        $this->loggers = $loggers;
    }

    /**
     * Log a message to the logs.
     *
     * @param mixed              $level
     * @param string|\Stringable $message
     * @param mixed[]            $context
     *
     * @return void
     */
    public function log(mixed $level, string|\Stringable $message, array $context = []): void
    {
        foreach ($this->loggers as $logger) {
            $logger->log($level, $message, $context);
        }
    }
}
