<?php

namespace Bugsnag\BugsnagLaravel;

use Bugsnag\Breadcrumbs\Breadcrumb;
use Bugsnag\BugsnagLaravel\Middleware\UnhandledState;
use Bugsnag\BugsnagLaravel\Queue\Tracker;
use Bugsnag\BugsnagLaravel\Request\LaravelResolver;
use Bugsnag\Callbacks\CustomUser;
use Bugsnag\Client;
use Bugsnag\Configuration;
use Bugsnag\FeatureFlag;
use Bugsnag\PsrLogger\BugsnagLogger;
use Bugsnag\PsrLogger\MultiLogger as BaseMultiLogger;
use Bugsnag\Report;
use DateTime;
use Illuminate\Auth\GenericUser;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Log\LogManager;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Queue\QueueManager;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;
use Monolog\Handler\PsrHandler;
use Monolog\Logger;
use ReflectionClass;

class BugsnagServiceProvider extends ServiceProvider
{
    /**
     * The package version.
     *
     * @var string
     */
    const VERSION = '2.26.0';

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupConfig($this->app);

        $this->setupEvents($this->app->events, $this->app->config->get('bugsnag'));

        $this->setupQueue($this->app->queue);

        // Load the Client instance up-front if the OOM bootstrapper has been
        // loaded. This avoids the possibility of initialising during an OOM,
        // which can take a non-trivial amount of memory
        if (class_exists(OomBootstrapper::class, false) && !$this->app->runningUnitTests()) {
            $this->app->make('bugsnag');
        }
    }

    /**
     * Setup the config.
     *
     * @param \Illuminate\Contracts\Container\Container $app
     *
     * @return void
     */
    protected function setupConfig(Container $app)
    {
        $source = realpath($raw = __DIR__.'/../config/bugsnag.php') ?: $raw;

        if ($app instanceof LaravelApplication && $app->runningInConsole()) {
            $this->publishes([$source => config_path('bugsnag.php')]);
        } elseif ($app instanceof LumenApplication) {
            $app->configure('bugsnag');
        }

        $this->mergeConfigFrom($source, 'bugsnag');
    }

    /**
     * Setup the events.
     *
     * @param \Illuminate\Contracts\Events\Dispatcher $events
     * @param array                                   $config
     *
     * @return void
     */
    protected function setupEvents(Dispatcher $events, array $config)
    {
        if ($this->isSessionTrackingAllowed($config)) {
            $events->listen(RouteMatched::class, function ($event) {
                $this->app->bugsnag->getSessionTracker()->startSession();
            });
        }

        if (isset($config['query']) && !$config['query']) {
            return;
        }

        $show = isset($config['bindings']) && $config['bindings'];

        if (class_exists(QueryExecuted::class)) {
            $events->listen(QueryExecuted::class, function (QueryExecuted $query) use ($show) {
                $this->app->bugsnag->leaveBreadcrumb(
                    'Query executed',
                    Breadcrumb::PROCESS_TYPE,
                    $this->formatQuery($query->sql, $show ? $query->bindings : [], $query->time, $query->connectionName)
                );
            });
        } else {
            $events->listen('illuminate.query', function ($sql, array $bindings, $time, $connection) use ($show) {
                $this->app->bugsnag->leaveBreadcrumb(
                    'Query executed',
                    Breadcrumb::PROCESS_TYPE,
                    $this->formatQuery($sql, $show ? $bindings : [], $time, $connection)
                );
            });
        }
    }

    /**
     * Format the query as breadcrumb metadata.
     *
     * @param string $sql
     * @param array  $bindings
     * @param float  $time
     * @param string $connection
     *
     * @return array
     */
    protected function formatQuery($sql, array $bindings, $time, $connection)
    {
        $data = ['sql' => $sql];

        foreach ($bindings as $index => $binding) {
            $data["binding {$index}"] = $binding;
        }

        $data['time'] = "{$time}ms";
        $data['connection'] = $connection;

        return $data;
    }

    /**
     * Setup the queue.
     *
     * @param \Illuminate\Queue\QueueManager $queue
     *
     * @return void
     */
    protected function setupQueue(QueueManager $queue)
    {
        $queue->looping(function () {
            $this->app->bugsnag->flush();
            $this->app->bugsnag->clearBreadcrumbs();
            $this->app->make(Tracker::class)->clear();
        });

        if (!class_exists(JobProcessing::class)) {
            return;
        }

        $queue->before(function (JobProcessing $event) {
            $this->app->bugsnag->setFallbackType('Queue');

            $job = [
                'name' => $event->job->getName(),
                'queue' => $event->job->getQueue(),
                'attempts' => $event->job->attempts(),
                'connection' => $event->connectionName,
            ];

            if (method_exists($event->job, 'resolveName')) {
                $job['resolved'] = $event->job->resolveName();
            }

            $this->app->make(Tracker::class)->set($job);
        });

        $this->setupQueueForLaravelVapor($queue);
    }

    /**
     * Setup queue events for Laravel Vapor.
     *
     * This is required because Laravel Vapor's queue system doesn't behave as
     * a daemonised queue worker (the 'looping' event never fires) but also
     * doesn't behave as a non-daemonised queue worker (our shutdown function
     * never fires).
     *
     * @param QueueManager $queue
     *
     * @return void
     */
    private function setupQueueForLaravelVapor(QueueManager $queue)
    {
        // ensure we're running on vapor
        // this is how vapor-core does it, e.g.:
        // https://github.com/laravel/vapor-core/blob/61437221090850ba6e51dce15d0058d362654f9b/src/ConfiguresAssets.php#L16-L19
        if (!isset($_ENV['VAPOR_SSM_PATH'])) {
            return;
        }

        // used to keep track of if we're the ones disabling batch sending, so we
        // know if we need to re-enable it - if the user disables batch sending
        // then they don't want it enabled at all
        static $batchSendingWasDisabledByUs = false;

        $queue->before(function () use (&$batchSendingWasDisabledByUs) {
            // clear breadcrumbs to stop them leaking between jobs
            $this->app->bugsnag->clearBreadcrumbs();

            // only re-enable batch sending if we're the ones disabling it
            // this allows users to disable batch sending entirely
            if ($batchSendingWasDisabledByUs) {
                $this->app->bugsnag->setBatchSending(true);
            }
        });

        $flush = function () use (&$batchSendingWasDisabledByUs) {
            // flush any events created in this job
            $this->app->bugsnag->flush();

            // disable batch sending so any events after this get sent synchronously
            // this is important as exceptions are logged after the 'exceptionOccurred'
            // event fires, so the above flush is too early to send them
            // these exceptions would get sent after processing the next queued job,
            // but we'd still drop the last event when this queue worker stops running
            if ($this->app->bugsnag->isBatchSending()) {
                $this->app->bugsnag->setBatchSending(false);
                $batchSendingWasDisabledByUs = true;
            }
        };

        // added in 5.2.41
        if (method_exists($queue, 'after')) {
            $queue->after($flush);
        }

        // added in 5.2.41
        if (method_exists($queue, 'exceptionOccurred')) {
            $queue->exceptionOccurred($flush);
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('bugsnag', function (Container $app) {
            $config = $app->config->get('bugsnag');
            $client = new Client(new Configuration($config['api_key']), new LaravelResolver($app), $this->getGuzzle($config));

            $this->setupCallbacks($client, $app, $config);
            $this->setupPaths($client, $app, $config);

            $client->setReleaseStage(isset($config['release_stage']) ? $config['release_stage'] : $app->environment());
            $client->setHostname(isset($config['hostname']) ? $config['hostname'] : null);
            $client->getConfig()->mergeDeviceData(['runtimeVersions' => $this->getRuntimeVersion()]);

            $client->setFallbackType($app->runningInConsole() ? 'Console' : 'HTTP');
            $client->setAppType(isset($config['app_type']) ? $config['app_type'] : null);
            $client->setAppVersion(isset($config['app_version']) ? $config['app_version'] : null);
            $client->setBatchSending(isset($config['batch_sending']) ? $config['batch_sending'] : true);
            $client->setSendCode(isset($config['send_code']) ? $config['send_code'] : true);
            $client->getPipeline()->insertBefore(new UnhandledState(), 'Bugsnag\\Middleware\\SessionData');

            $client->setNotifier([
                'name' => 'Bugsnag Laravel',
                'version' => static::VERSION,
                'url' => 'https://github.com/bugsnag/bugsnag-laravel',
            ]);

            if (isset($config['notify_release_stages']) && is_array($config['notify_release_stages'])) {
                $client->setNotifyReleaseStages($config['notify_release_stages']);
            }

            if (isset($config['filters']) && is_array($config['filters'])) {
                $client->setFilters($config['filters']);
            }

            if (isset($config['endpoint'])) {
                $client->setNotifyEndpoint($config['endpoint']);
            }

            if ($this->isSessionTrackingAllowed($config)) {
                $endpoint = isset($config['session_endpoint']) ? $config['session_endpoint'] : null;
                $this->setupSessionTracking($client, $endpoint, $this->app->events);
            }

            if (isset($config['build_endpoint'])) {
                $client->setBuildEndpoint($config['build_endpoint']);
            }

            if (array_key_exists('memory_limit_increase', $config)) {
                $client->setMemoryLimitIncrease($config['memory_limit_increase']);
            }

            if (isset($config['discard_classes']) && is_array($config['discard_classes'])) {
                $client->setDiscardClasses($config['discard_classes']);
            }

            if (isset($config['redacted_keys']) && is_array($config['redacted_keys'])) {
                $client->setRedactedKeys($config['redacted_keys']);
            }

            if (isset($config['feature_flags']) && is_array($config['feature_flags']) && $config['feature_flags'] !== []) {
                $featureFlags = [];

                foreach ($config['feature_flags'] as $flag) {
                    if (!is_array($flag) || !array_key_exists('name', $flag)) {
                        continue;
                    }

                    if (array_key_exists('variant', $flag)) {
                        $featureFlags[] = new FeatureFlag($flag['name'], $flag['variant']);
                    } else {
                        $featureFlags[] = new FeatureFlag($flag['name']);
                    }
                }

                $client->addFeatureFlags($featureFlags);
            }

            if (isset($config['max_breadcrumbs'])) {
                $client->setMaxBreadcrumbs($config['max_breadcrumbs']);
            }

            return $client;
        });

        $this->app->singleton('bugsnag.tracker', function () {
            return new Tracker();
        });

        $this->app->singleton('bugsnag.logger', function (Container $app) {
            $config = $app->config->get('bugsnag');
            $logger = interface_exists(Log::class) ? new LaravelLogger($app['bugsnag'], $app['events']) : new BugsnagLogger($app['bugsnag']);
            if (isset($config['logger_notify_level'])) {
                $logger->setNotifyLevel($config['logger_notify_level']);
            }

            return $logger;
        });

        $this->app->singleton('bugsnag.multi', function (Container $app) {
            return interface_exists(Log::class) ? new MultiLogger([$app['log'], $app['bugsnag.logger']]) : new BaseMultiLogger([$app['log'], $app['bugsnag.logger']]);
        });

        if ($this->app['log'] instanceof LogManager) {
            $this->app['log']->extend('bugsnag', function (Container $app, array $config) {
                $handler = new PsrHandler($app['bugsnag.logger']);

                return new Logger('bugsnag', [$handler]);
            });
        }

        $this->app->alias('bugsnag', Client::class);
        $this->app->alias('bugsnag.tracker', Tracker::class);
        $this->app->alias('bugsnag.logger', interface_exists(Log::class) ? LaravelLogger::class : BugsnagLogger::class);
        $this->app->alias('bugsnag.multi', interface_exists(Log::class) ? MultiLogger::class : BaseMultiLogger::class);
    }

    /**
     * Get the guzzle client instance.
     *
     * @param array $config
     *
     * @return \GuzzleHttp\ClientInterface
     */
    protected function getGuzzle(array $config)
    {
        // If a 'bugsnag.guzzle' instance exists in the container, use it
        if ($this->app->bound('bugsnag.guzzle')) {
            return $this->app->make('bugsnag.guzzle');
        }

        $options = [];

        if (isset($config['proxy']) && $config['proxy']) {
            if (isset($config['proxy']['http']) && php_sapi_name() != 'cli') {
                unset($config['proxy']['http']);
            }

            $options['proxy'] = $config['proxy'];
        }

        return Client::makeGuzzle(null, $options);
    }

    /**
     * Setup the callbacks.
     *
     * @param \Bugsnag\Client                           $client
     * @param \Illuminate\Contracts\Container\Container $app
     * @param array                                     $config
     *
     * @return void
     */
    protected function setupCallbacks(Client $client, Container $app, array $config)
    {
        if (!isset($config['callbacks']) || $config['callbacks']) {
            $client->registerDefaultCallbacks();

            $client->registerCallback(function (Report $report) use ($app) {
                $tracker = $app->make(Tracker::class);

                if ($context = $tracker->context()) {
                    $report->setContext($context);
                }

                if ($job = $tracker->get()) {
                    $report->setMetaData(['job' => $job]);
                }
            });
        }

        if (!isset($config['user']) || $config['user']) {
            $client->registerCallback(new CustomUser(function () use ($app) {
                if ($user = $app->auth->user()) {
                    if (method_exists($user, 'attributesToArray') && is_callable([$user, 'attributesToArray'])) {
                        return $user->attributesToArray();
                    }

                    if ($user instanceof GenericUser) {
                        $reflection = new ReflectionClass($user);
                        $property = $reflection->getProperty('attributes');
                        $property->setAccessible(true);

                        return $property->getValue($user);
                    }
                }
            }));
        }
    }

    /**
     * Setup the client paths.
     *
     * @param \Bugsnag\Client                           $client
     * @param \Illuminate\Contracts\Container\Container $app
     * @param array                                     $config
     *
     * @return void
     */
    protected function setupPaths(Client $client, Container $app, array $config)
    {
        if (isset($config['project_root_regex'])) {
            $client->setProjectRootRegex($config['project_root_regex']);
        } elseif (isset($config['project_root'])) {
            $client->setProjectRoot($config['project_root']);
        } else {
            $client->setProjectRoot($app->path());
        }

        if (isset($config['strip_path_regex'])) {
            $client->setStripPathRegex($config['strip_path_regex']);
        } elseif (isset($config['strip_path'])) {
            $client->setStripPath($config['strip_path']);
        } else {
            $client->setStripPath($app->basePath());
        }
    }

    /**
     * Setup session tracking.
     *
     * @param \Bugsnag\Client $client
     * @param string          $endpoint
     *
     * @return void
     */
    protected function setupSessionTracking(Client $client, $endpoint, $events)
    {
        $client->setAutoCaptureSessions(true);
        if (!is_null($endpoint)) {
            $client->setSessionEndpoint($endpoint);
        }
        $sessionTracker = $client->getSessionTracker();

        $sessionStorage = function ($session = null) {
            if (is_null($session)) {
                return session('bugsnag-session', []);
            } else {
                session(['bugsnag-session' => $session]);
            }
        };

        $sessionTracker->setSessionFunction($sessionStorage);

        $cache = $this->app->cache;

        $genericStorage = function ($key, $value = null) use ($cache) {
            if (is_null($value)) {
                return $cache->get($key, null);
            } else {
                $cache->put($key, $value, new DateTime('+ 1 hour'));
            }
        };

        $sessionTracker->setStorageFunction($genericStorage);
    }

    /**
     * Returns the framework name and version to add to the device data.
     *
     * Attempt to parse a semantic framework version from $app or else return
     * the full version string.
     * e.g. Lumen: "Lumen (x.x.x) (Laravel Components y.y.*)" => "x.x.x"
     *
     * @return array
     */
    protected function getRuntimeVersion()
    {
        $version = $this->app->version();
        if (preg_match('/(\d+\.\d+\.\d+)/', $version, $versionMatches)) {
            $version = $versionMatches[0];
        }

        return [($this->app instanceof LumenApplication ? 'lumen' : 'laravel') => $version];
    }

    /**
     * Tests whether session tracking can/should be enabled.
     *
     * @param array $config The configuration array
     *
     * @return bool true if session tracking should be enabled.
     */
    protected function isSessionTrackingAllowed($config)
    {
        // Session support removed in Lumen 5.3 - only setup automatic session
        // tracking if the session function is avaiable
        return isset($config['auto_capture_sessions'])
               && $config['auto_capture_sessions']
               && function_exists('session');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['bugsnag', 'bugsnag.tracker', 'bugsnag.logger', 'bugsnag.multi'];
    }
}
