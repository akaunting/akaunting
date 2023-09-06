<?php

namespace Sentry\Laravel\Features;

use Illuminate\Contracts\Container\Container;
use Sentry\Laravel\BaseServiceProvider;
use Sentry\SentrySdk;
use Throwable;

/**
 * @method void onBoot() Setup the feature in the environment.
 * @method void onBootInactive() Setup the feature in the environment in an inactive state (when no DSN was set).
 *
 * @internal
 */
abstract class Feature
{
    /**
     * @var Container The Laravel application container.
     */
    private $container;

    /**
     * In-memory cache for the tracing feature flag.
     *
     * @var bool|null
     */
    private $isTracingFeatureEnabled;

    /**
     * In-memory cache for the breadcumb feature flag.
     *
     * @var bool|null
     */
    private $isBreadcrumbFeatureEnabled;

    /**
     * @param Container $container The Laravel application container.
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Indicates if the feature is applicable to the current environment.
     *
     * @return bool
     */
    abstract public function isApplicable(): bool;

    /**
     * Register the feature in the environment.
     */
    public function register(): void
    {
        // ...
    }

    /**
     * Initialize the feature.
     */
    public function boot(): void
    {
        if (method_exists($this, 'onBoot') && $this->isApplicable()) {
            try {
                $this->container->call([$this, 'onBoot']);
            } catch (Throwable $exception) {
                // If the feature setup fails, we don't want to prevent the rest of the SDK from working.
            }
        }
    }

    /**
     * Initialize the feature in an inactive state (when no DSN was set).
     */
    public function bootInactive(): void
    {
        if (method_exists($this, 'onBootInactive') && $this->isApplicable()) {
            try {
                $this->container->call([$this, 'onBootInactive']);
            } catch (Throwable $exception) {
                // If the feature setup fails, we don't want to prevent the rest of the SDK from working.
            }
        }
    }

    /**
     * Retrieve the Laravel application container.
     *
     * @return Container
     */
    protected function container(): Container
    {
        return $this->container;
    }

    /**
     * Retrieve the user configuration.
     *
     * @return array
     */
    protected function getUserConfig(): array
    {
        $config = $this->container['config'][BaseServiceProvider::$abstract];

        return empty($config) ? [] : $config;
    }

    /**
     * Should default PII be sent by default.
     */
    protected function shouldSendDefaultPii(): bool
    {
        $client = SentrySdk::getCurrentHub()->getClient();

        if ($client === null) {
            return false;
        }

        return $client->getOptions()->shouldSendDefaultPii();
    }

    /**
     * Indicates if the given feature is enabled for tracing.
     */
    protected function isTracingFeatureEnabled(string $feature, bool $default = true): bool
    {
        if ($this->isTracingFeatureEnabled === null) {
            $this->isTracingFeatureEnabled = $this->isFeatureEnabled('tracing', $feature, $default);
        }

        return $this->isTracingFeatureEnabled;
    }

    /**
     * Indicates if the given feature is enabled for breadcrumbs.
     */
    protected function isBreadcrumbFeatureEnabled(string $feature, bool $default = true): bool
    {
        if ($this->isBreadcrumbFeatureEnabled === null) {
            $this->isBreadcrumbFeatureEnabled = $this->isFeatureEnabled('breadcrumbs', $feature, $default);
        }

        return $this->isBreadcrumbFeatureEnabled;
    }

    /**
     * Helper to test if a certain feature is enabled in the user config.
     */
    private function isFeatureEnabled(string $category, string $feature, bool $default): bool
    {
        $config = $this->getUserConfig()[$category] ?? [];

        return ($config[$feature] ?? $default) === true;
    }
}
