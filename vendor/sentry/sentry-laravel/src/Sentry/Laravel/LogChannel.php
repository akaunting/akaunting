<?php

namespace Sentry\Laravel;

use Monolog\Handler\FingersCrossedHandler;
use Monolog\Logger;
use Illuminate\Log\LogManager;
use Sentry\State\HubInterface;

class LogChannel extends LogManager
{
    /**
     * @param array $config
     *
     * @return Logger
     */
    public function __invoke(array $config = []): Logger
    {
        $handler = new SentryHandler(
            $this->app->make(HubInterface::class),
            $config['level'] ?? Logger::DEBUG,
            $config['bubble'] ?? true,
            $config['report_exceptions'] ?? true,
            isset($config['formatter']) && $config['formatter'] !== 'default'
        );

        if (isset($config['action_level'])) {
            $handler = new FingersCrossedHandler($handler, $config['action_level']);

            // Consume the `action_level` config option since newer Laravel versions also support this option
            // and will wrap the handler again in another `FingersCrossedHandler` if we leave the option set
            // See: https://github.com/laravel/framework/pull/40305 (release v8.79.0)
            unset($config['action_level']);
        }

        return new Logger(
            $this->parseChannel($config),
            [
                $this->prepareHandler($handler, $config),
            ]
        );
    }
}
