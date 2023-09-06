<?php

namespace Sentry\Laravel;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Http\Kernel as HttpKernelInterface;
use Illuminate\Foundation\Application as Laravel;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Http\Request;
use Laravel\Lumen\Application as Lumen;
use RuntimeException;
use Sentry\ClientBuilder;
use Sentry\ClientBuilderInterface;
use Sentry\Event;
use Sentry\EventHint;
use Sentry\Integration as SdkIntegration;
use Sentry\Laravel\Console\AboutCommandIntegration;
use Sentry\Laravel\Console\PublishCommand;
use Sentry\Laravel\Console\TestCommand;
use Sentry\Laravel\Features\Feature;
use Sentry\Laravel\Http\LaravelRequestFetcher;
use Sentry\Laravel\Http\SetRequestIpMiddleware;
use Sentry\Laravel\Http\SetRequestMiddleware;
use Sentry\Laravel\Tracing\BacktraceHelper;
use Sentry\Laravel\Tracing\ServiceProvider as TracingServiceProvider;
use Sentry\SentrySdk;
use Sentry\Serializer\RepresentationSerializer;
use Sentry\State\Hub;
use Sentry\State\HubInterface;
use Sentry\Tracing\TransactionMetadata;
use Throwable;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * List of configuration options that are Laravel specific and should not be sent to the base PHP SDK.
     */
    protected const LARAVEL_SPECIFIC_OPTIONS = [
        // We do not want these settings to hit the PHP SDK because they are Laravel specific and the PHP SDK will throw errors
        'tracing',
        'breadcrumbs',
        // We resolve the integrations through the container later, so we initially do not pass it to the SDK yet
        'integrations',
        // This is kept for backwards compatibility and can be dropped in a future breaking release
        'breadcrumbs.sql_bindings',

        // This config option is no longer in use but to prevent errors when upgrading we leave it here to be discarded
        'controllers_base_namespace',
    ];

    /**
     * List of features that are provided by the SDK.
     */
    protected const FEATURES = [
        Features\LogIntegration::class,
        Features\CacheIntegration::class,
        Features\QueueIntegration::class,
        Features\ConsoleIntegration::class,
        Features\FolioPackageIntegration::class,
        Features\Storage\Integration::class,
        Features\LivewirePackageIntegration::class,
    ];

    /**
     * Boot the service provider.
     */
    public function boot(): void
    {
        $this->app->make(HubInterface::class);

        $this->bootFeatures();

        if ($this->hasDsnSet()) {
            $this->bindEvents();

            if ($this->app instanceof Lumen) {
                $this->app->middleware(SetRequestMiddleware::class);
                $this->app->middleware(SetRequestIpMiddleware::class);
            } elseif ($this->app->bound(HttpKernelInterface::class)) {
                $httpKernel = $this->app->make(HttpKernelInterface::class);

                if ($httpKernel instanceof HttpKernel) {
                    $httpKernel->pushMiddleware(SetRequestMiddleware::class);
                    $httpKernel->pushMiddleware(SetRequestIpMiddleware::class);
                }
            }
        }

        if ($this->app->runningInConsole()) {
            if ($this->app instanceof Laravel) {
                $this->publishes([
                    __DIR__ . '/../../../config/sentry.php' => config_path(static::$abstract . '.php'),
                ], 'config');
            }

            $this->registerArtisanCommands();
        }

        $this->registerAboutCommandIntegration();
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        if ($this->app instanceof Lumen) {
            $this->app->configure(static::$abstract);
        }

        $this->mergeConfigFrom(__DIR__ . '/../../../config/sentry.php', static::$abstract);

        $this->configureAndRegisterClient();

        $this->registerFeatures();
    }

    /**
     * Bind to the Laravel event dispatcher to log events.
     */
    protected function bindEvents(): void
    {
        $userConfig = $this->getUserConfig();

        $handler = new EventHandler($this->app, $userConfig);

        try {
            /** @var \Illuminate\Contracts\Events\Dispatcher $dispatcher */
            $dispatcher = $this->app->make(Dispatcher::class);

            $handler->subscribe($dispatcher);

            if ($this->app->bound('octane')) {
                $handler->subscribeOctaneEvents($dispatcher);
            }

            if (isset($userConfig['send_default_pii']) && $userConfig['send_default_pii'] !== false) {
                $handler->subscribeAuthEvents($dispatcher);
            }
        } catch (BindingResolutionException $e) {
            // If we cannot resolve the event dispatcher we also cannot listen to events
        }
    }

    /**
     * Bind and register all the features.
     */
    protected function registerFeatures(): void
    {
        // Register all the features as singletons, so there is only one instance of each feature in the application
        foreach (self::FEATURES as $feature) {
            $this->app->singleton($feature);
        }

        foreach (self::FEATURES as $feature) {
            try {
                /** @var Feature $featureInstance */
                $featureInstance = $this->app->make($feature);

                $featureInstance->register();
            } catch (Throwable $e) {
                // Ensure that features do not break the whole application
            }
        }
    }

    /**
     * Boot all the features.
     */
    protected function bootFeatures(): void
    {
        $bootActive = $this->hasDsnSet();

        foreach (self::FEATURES as $feature) {
            try {
                /** @var Feature $featureInstance */
                $featureInstance = $this->app->make($feature);

                $bootActive
                    ? $featureInstance->boot()
                    : $featureInstance->bootInactive();
            } catch (Throwable $e) {
                // Ensure that features do not break the whole application
            }
        }
    }

    /**
     * Register the artisan commands.
     */
    protected function registerArtisanCommands(): void
    {
        $this->commands([
            TestCommand::class,
            PublishCommand::class,
        ]);
    }

    /**
     * Register the `php artisan about` command integration.
     */
    protected function registerAboutCommandIntegration(): void
    {
        // The about command is only available in Laravel 9 and up so we need to check if it's available to us
        if (!class_exists(AboutCommand::class)) {
            return;
        }

        AboutCommand::add('Sentry', AboutCommandIntegration::class);
    }

    /**
     * Configure and register the Sentry client with the container.
     */
    protected function configureAndRegisterClient(): void
    {
        $this->app->bind(ClientBuilderInterface::class, function () {
            $basePath = base_path();
            $userConfig = $this->getUserConfig();

            foreach (static::LARAVEL_SPECIFIC_OPTIONS as $laravelSpecificOptionName) {
                unset($userConfig[$laravelSpecificOptionName]);
            }

            $options = \array_merge(
                [
                    'prefixes' => [$basePath],
                    'in_app_exclude' => ["{$basePath}/vendor"],
                ],
                $userConfig
            );

            // When we get no environment from the (user) configuration we default to the Laravel environment
            if (empty($options['environment'])) {
                $options['environment'] = $this->app->environment();
            }

            if ($this->app instanceof Lumen) {
                $wrapBeforeSend = function (?callable $userBeforeSend) {
                    return function (Event $event, ?EventHint $eventHint) use ($userBeforeSend) {
                        $request = $this->app->make(Request::class);

                        if ($request !== null) {
                            $route = $request->route();

                            if ($route !== null) {
                                [$routeName, $transactionSource] = Integration::extractNameAndSourceForLumenRoute($request->route(), $request->path());

                                $event->setTransaction($routeName);

                                $transactionMetadata = $event->getSdkMetadata('transaction_metadata');

                                if ($transactionMetadata instanceof TransactionMetadata) {
                                    $transactionMetadata->setSource($transactionSource);
                                }
                            }
                        }

                        if ($userBeforeSend !== null) {
                            return $userBeforeSend($event, $eventHint);
                        }

                        return $event;
                    };
                };

                $options['before_send'] = $wrapBeforeSend($options['before_send'] ?? null);
                $options['before_send_transaction'] = $wrapBeforeSend($options['before_send_transaction'] ?? null);
            }

            $clientBuilder = ClientBuilder::create($options);

            // Set the Laravel SDK identifier and version
            $clientBuilder->setSdkIdentifier(Version::SDK_IDENTIFIER);
            $clientBuilder->setSdkVersion(Version::SDK_VERSION);

            return $clientBuilder;
        });

        $this->app->singleton(HubInterface::class, function () {
            /** @var \Sentry\ClientBuilderInterface $clientBuilder */
            $clientBuilder = $this->app->make(ClientBuilderInterface::class);

            $options = $clientBuilder->getOptions();

            $userIntegrations = $this->resolveIntegrationsFromUserConfig();

            $options->setIntegrations(function (array $integrations) use ($options, $userIntegrations) {
                if ($options->hasDefaultIntegrations()) {
                    // Remove the default error and fatal exception listeners to let Laravel handle those
                    // itself. These event are still bubbling up through the documented changes in the users
                    // `ExceptionHandler` of their application or through the log channel integration to Sentry
                    $integrations = array_filter($integrations, static function (SdkIntegration\IntegrationInterface $integration): bool {
                        if ($integration instanceof SdkIntegration\ErrorListenerIntegration) {
                            return false;
                        }

                        if ($integration instanceof SdkIntegration\ExceptionListenerIntegration) {
                            return false;
                        }

                        if ($integration instanceof SdkIntegration\FatalErrorListenerIntegration) {
                            return false;
                        }

                        // We also remove the default request integration so it can be readded
                        // after with a Laravel specific request fetcher. This way we can resolve
                        // the request from Laravel instead of constructing it from the global state
                        if ($integration instanceof SdkIntegration\RequestIntegration) {
                            return false;
                        }

                        return true;
                    });

                    $integrations[] = new SdkIntegration\RequestIntegration(
                        new LaravelRequestFetcher
                    );
                }

                return array_merge($integrations, $userIntegrations);
            });

            $hub = new Hub($clientBuilder->getClient());

            SentrySdk::setCurrentHub($hub);

            return $hub;
        });

        $this->app->alias(HubInterface::class, static::$abstract);

        $this->app->singleton(BacktraceHelper::class, function () {
            $sentry = $this->app->make(HubInterface::class);

            $options = $sentry->getClient()->getOptions();

            return new BacktraceHelper($options, new RepresentationSerializer($options));
        });
    }

    /**
     * Resolve the integrations from the user configuration with the container.
     *
     * @return array
     */
    private function resolveIntegrationsFromUserConfig(): array
    {
        // Default Sentry Laravel SDK integrations
        $integrations = [
            new Integration,
            new Integration\ExceptionContextIntegration,
        ];

        $userConfig = $this->getUserConfig();

        $integrationsToResolve = array_merge($userConfig['integrations'] ?? []);

        $enableDefaultTracingIntegrations = $userConfig['tracing']['default_integrations'] ?? true;

        if ($enableDefaultTracingIntegrations) {
            $integrationsToResolve = array_merge($integrationsToResolve, TracingServiceProvider::DEFAULT_INTEGRATIONS);
        }

        foreach ($integrationsToResolve as $userIntegration) {
            if ($userIntegration instanceof SdkIntegration\IntegrationInterface) {
                $integrations[] = $userIntegration;
            } elseif (\is_string($userIntegration)) {
                $resolvedIntegration = $this->app->make($userIntegration);

                if (!$resolvedIntegration instanceof SdkIntegration\IntegrationInterface) {
                    throw new RuntimeException(
                        sprintf(
                            'Sentry integrations must be an instance of `%s` got `%s`.',
                            SdkIntegration\IntegrationInterface::class,
                            get_class($resolvedIntegration)
                        )
                    );
                }

                $integrations[] = $resolvedIntegration;
            } else {
                throw new RuntimeException(
                    sprintf(
                        'Sentry integrations must either be a valid container reference or an instance of `%s`.',
                        SdkIntegration\IntegrationInterface::class
                    )
                );
            }
        }

        return $integrations;
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [static::$abstract];
    }
}
