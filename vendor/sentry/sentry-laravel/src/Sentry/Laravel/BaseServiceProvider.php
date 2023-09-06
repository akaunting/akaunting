<?php

namespace Sentry\Laravel;

use Illuminate\Support\ServiceProvider;

abstract class BaseServiceProvider extends ServiceProvider
{
    /**
     * Abstract type to bind Sentry as in the Service Container.
     *
     * @var string
     */
    public static $abstract = 'sentry';

    /**
     * Check if a DSN was set in the config.
     *
     * @return bool
     */
    protected function hasDsnSet(): bool
    {
        $config = $this->getUserConfig();

        return !empty($config['dsn']);
    }

    /**
     * Retrieve the user configuration.
     *
     * @return array
     */
    protected function getUserConfig(): array
    {
        $config = $this->app['config'][static::$abstract];

        return empty($config) ? [] : $config;
    }

    /**
     * Checks if the config is set in such a way that performance tracing could be enabled.
     *
     * Because of `traces_sampler` being dynamic we can never be 100% confident but that is also not important.
     *
     * @return bool
     */
    protected function couldHavePerformanceTracingEnabled(): bool
    {
        $config = $this->getUserConfig();

        return !empty($config['traces_sample_rate']) || !empty($config['traces_sampler']);
    }
}
