<?php

namespace Bugsnag;

use Bugsnag\Breadcrumbs\Breadcrumb;
use Bugsnag\Breadcrumbs\Recorder;
use Bugsnag\Callbacks\GlobalMetaData;
use Bugsnag\Callbacks\RequestContext;
use Bugsnag\Callbacks\RequestMetaData;
use Bugsnag\Callbacks\RequestSession;
use Bugsnag\Callbacks\RequestUser;
use Bugsnag\Internal\GuzzleCompat;
use Bugsnag\Middleware\BreadcrumbData;
use Bugsnag\Middleware\CallbackBridge;
use Bugsnag\Middleware\DiscardClasses;
use Bugsnag\Middleware\NotificationSkipper;
use Bugsnag\Middleware\SessionData;
use Bugsnag\Request\BasicResolver;
use Bugsnag\Request\ResolverInterface;
use Bugsnag\Shutdown\PhpShutdownStrategy;
use Bugsnag\Shutdown\ShutdownStrategyInterface;
use Composer\CaBundle\CaBundle;
use GuzzleHttp;

class Client implements FeatureDataStore
{
    /**
     * The default event notification endpoint.
     *
     * @var string
     *
     * @deprecated Use {@see Configuration::NOTIFY_ENDPOINT} instead.
     */
    const ENDPOINT = Configuration::NOTIFY_ENDPOINT;

    /**
     * The config instance.
     *
     * @var \Bugsnag\Configuration
     */
    protected $config;

    /**
     * The request resolver instance.
     *
     * @var \Bugsnag\Request\ResolverInterface
     */
    protected $resolver;

    /**
     * The breadcrumb recorder instance.
     *
     * @var \Bugsnag\Breadcrumbs\Recorder
     */
    protected $recorder;

    /**
     * The notification pipeline instance.
     *
     * @var \Bugsnag\Pipeline
     */
    protected $pipeline;

    /**
     * The http client instance.
     *
     * @var \Bugsnag\HttpClient
     */
    protected $http;

    /**
     * The session tracker instance.
     *
     * @var \Bugsnag\SessionTracker
     */
    protected $sessionTracker;

    /**
     * Default HTTP timeout, in seconds.
     *
     * @internal
     *
     * @var float
     */
    const DEFAULT_TIMEOUT_S = 15.0;

    /**
     * Make a new client instance.
     *
     * If you don't pass in a key, we'll try to read it from the env variables.
     *
     * @param string|null $apiKey         your bugsnag api key
     * @param string|null $notifyEndpoint your bugsnag notify endpoint
     * @param bool        $defaults       if we should register our default callbacks
     *
     * @return static
     */
    public static function make(
        $apiKey = null,
        $notifyEndpoint = null,
        $defaults = true
    ) {
        $env = new Env();

        $config = new Configuration($apiKey ?: $env->get('BUGSNAG_API_KEY'));
        $guzzle = static::makeGuzzle($notifyEndpoint ?: $env->get('BUGSNAG_ENDPOINT'));

        // @phpstan-ignore-next-line
        $client = new static($config, null, $guzzle);

        if ($defaults) {
            $client->registerDefaultCallbacks();
        }

        return $client;
    }

    /**
     * @param \Bugsnag\Configuration $config
     * @param \Bugsnag\Request\ResolverInterface|null $resolver
     * @param \GuzzleHttp\ClientInterface|null $guzzle
     * @param \Bugsnag\Shutdown\ShutdownStrategyInterface|null $shutdownStrategy
     */
    public function __construct(
        Configuration $config,
        ResolverInterface $resolver = null,
        GuzzleHttp\ClientInterface $guzzle = null,
        ShutdownStrategyInterface $shutdownStrategy = null
    ) {
        $guzzle = $guzzle ?: self::makeGuzzle();

        $this->syncNotifyEndpointWithGuzzleBaseUri($config, $guzzle);

        $this->config = $config;
        $this->resolver = $resolver ?: new BasicResolver();
        $this->recorder = new Recorder();
        $this->pipeline = new Pipeline();
        $this->http = new HttpClient($config, $guzzle);
        $this->sessionTracker = new SessionTracker($config, $this->http);

        $this->registerMiddleware(new NotificationSkipper($config));
        $this->registerMiddleware(new DiscardClasses($config));
        $this->registerMiddleware(new BreadcrumbData($this->recorder));
        $this->registerMiddleware(new SessionData($this));

        // Shutdown strategy is used to trigger flush() calls when batch sending is enabled
        $shutdownStrategy = $shutdownStrategy ?: new PhpShutdownStrategy();
        $shutdownStrategy->registerShutdownStrategy($this);
    }

    /**
     * Make a new guzzle client instance.
     *
     * @param string|null $base
     * @param array       $options
     *
     * @return GuzzleHttp\ClientInterface
     */
    public static function makeGuzzle($base = null, array $options = [])
    {
        $options = self::resolveGuzzleOptions($base, $options);

        return new GuzzleHttp\Client($options);
    }

    /**
     * @param string|null $base
     * @param array $options
     *
     * @return array
     */
    private static function resolveGuzzleOptions($base, array $options)
    {
        $key = GuzzleCompat::getBaseUriOptionName();
        $options[$key] = $base ?: Configuration::NOTIFY_ENDPOINT;

        $path = static::getCaBundlePath();

        if ($path) {
            $options['verify'] = $path;
        }

        return GuzzleCompat::applyRequestOptions(
            $options,
            [
                'timeout' => self::DEFAULT_TIMEOUT_S,
                'connect_timeout' => self::DEFAULT_TIMEOUT_S,
            ]
        );
    }

    /**
     * Ensure the notify endpoint is synchronised with Guzzle's base URL.
     *
     * @param \Bugsnag\Configuration $configuration
     * @param \GuzzleHttp\ClientInterface $guzzle
     *
     * @return void
     */
    private function syncNotifyEndpointWithGuzzleBaseUri(
        Configuration $configuration,
        GuzzleHttp\ClientInterface $guzzle
    ) {
        // Don't change the endpoint if one is already set, otherwise we could be
        // resetting it back to the default as the Guzzle base URL will always
        // be set by 'makeGuzzle'.
        if ($configuration->getNotifyEndpoint() !== Configuration::NOTIFY_ENDPOINT) {
            return;
        }

        $base = GuzzleCompat::getBaseUri($guzzle);

        if (is_string($base) || (is_object($base) && method_exists($base, '__toString'))) {
            $configuration->setNotifyEndpoint((string) $base);
        }
    }

    /**
     * Get the ca bundle path if one exists.
     *
     * @return string|false
     */
    protected static function getCaBundlePath()
    {
        if (version_compare(PHP_VERSION, '5.6.0') >= 0 || !class_exists(CaBundle::class)) {
            return false;
        }

        return realpath(CaBundle::getSystemCaRootBundlePath());
    }

    /**
     * Get the config instance.
     *
     * @return \Bugsnag\Configuration
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Get the pipeline instance.
     *
     * @return \Bugsnag\Pipeline
     */
    public function getPipeline()
    {
        return $this->pipeline;
    }

    /**
     * Regsier a new notification callback.
     *
     * @param callable $callback
     *
     * @return $this
     */
    public function registerCallback(callable $callback)
    {
        $this->registerMiddleware(new CallbackBridge($callback));

        return $this;
    }

    /**
     * Regsier all our default callbacks.
     *
     * @return $this
     */
    public function registerDefaultCallbacks()
    {
        $this->registerCallback(new GlobalMetaData($this->config))
             ->registerCallback(new RequestMetaData($this->resolver))
             ->registerCallback(new RequestSession($this->resolver))
             ->registerCallback(new RequestUser($this->resolver))
             ->registerCallback(new RequestContext($this->resolver));

        return $this;
    }

    /**
     * Register a middleware object to the pipeline.
     *
     * @param callable $middleware
     *
     * @return $this
     */
    public function registerMiddleware(callable $middleware)
    {
        $this->pipeline->pipe($middleware);

        return $this;
    }

    /**
     * Record the given breadcrumb.
     *
     * @param string      $name     the name of the breadcrumb
     * @param string|null $type     the type of breadcrumb
     * @param array       $metaData additional information about the breadcrumb
     *
     * @return void
     */
    public function leaveBreadcrumb($name, $type = null, array $metaData = [])
    {
        $type = in_array($type, Breadcrumb::getTypes(), true) ? $type : Breadcrumb::MANUAL_TYPE;

        $this->recorder->record(new Breadcrumb($name, $type, $metaData));
    }

    /**
     * Clear all recorded breadcrumbs.
     *
     * @return void
     */
    public function clearBreadcrumbs()
    {
        $this->recorder->clear();
    }

    /**
     * Notify Bugsnag of a non-fatal/handled throwable.
     *
     * @param \Throwable    $throwable the throwable to notify Bugsnag about
     * @param callable|null $callback  the customization callback
     *
     * @return void
     */
    public function notifyException($throwable, callable $callback = null)
    {
        $report = Report::fromPHPThrowable($this->config, $throwable);

        $this->notify($report, $callback);
    }

    /**
     * Notify Bugsnag of a non-fatal/handled error.
     *
     * @param string        $name     the name of the error, a short (1 word) string
     * @param string        $message  the error message
     * @param callable|null $callback the customization callback
     *
     * @return void
     */
    public function notifyError($name, $message, callable $callback = null)
    {
        $report = Report::fromNamedError($this->config, $name, $message);

        $this->notify($report, $callback);
    }

    /**
     * Notify Bugsnag of the given error report.
     *
     * This may simply involve queuing it for later if we're batching.
     *
     * @param \Bugsnag\Report $report   the error report to send
     * @param callable|null   $callback the customization callback
     *
     * @return void
     */
    public function notify(Report $report, callable $callback = null)
    {
        $this->pipeline->execute($report, function ($report) use ($callback) {
            if ($callback) {
                $resolvedReport = null;

                $bridge = new CallbackBridge($callback);
                $bridge($report, function ($report) use (&$resolvedReport) {
                    $resolvedReport = $report;
                });
                if ($resolvedReport) {
                    $report = $resolvedReport;
                } else {
                    return;
                }
            }

            $this->http->queue($report);
        });

        $this->leaveBreadcrumb($report->getName(), Breadcrumb::ERROR_TYPE, $report->getSummary());

        if (!$this->config->isBatchSending()) {
            $this->flush();
        }
    }

    /**
     * Notify Bugsnag of a deployment.
     *
     * @param string|null $repository the repository from which you are deploying the code
     * @param string|null $branch     the source control branch from which you are deploying
     * @param string|null $revision   the source control revision you are currently deploying
     *
     * @return void
     *
     * @deprecated Use {@see Client::build} instead.
     */
    public function deploy($repository = null, $branch = null, $revision = null)
    {
        $this->build($repository, $revision);
    }

    /**
     * Notify Bugsnag of a build.
     *
     * @param string|null $repository  the repository from which you are deploying the code
     * @param string|null $revision    the source control revision you are currently deploying
     * @param string|null $provider    the provider of the source control for the build
     * @param string|null $builderName the name of who or what is making the build
     *
     * @return void
     */
    public function build($repository = null, $revision = null, $provider = null, $builderName = null)
    {
        $data = [];

        if ($repository) {
            $data['repository'] = $repository;
        }

        if ($revision) {
            $data['revision'] = $revision;
        }

        if ($provider) {
            $data['provider'] = $provider;
        }

        if ($builderName) {
            $data['builder'] = $builderName;
        }

        $this->http->sendBuildReport($data);
    }

    /**
     * Flush any buffered reports.
     *
     * @return void
     */
    public function flush()
    {
        $this->http->sendEvents();
    }

    /**
     * Start tracking a session.
     *
     * @return void
     */
    public function startSession()
    {
        $this->sessionTracker->startSession();
    }

    /**
     * Returns the session tracker.
     *
     * @return \Bugsnag\SessionTracker
     */
    public function getSessionTracker()
    {
        return $this->sessionTracker;
    }

    // Forward calls to Configuration:

    /**
     * Get the Bugsnag API Key.
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->config->getApiKey();
    }

    /**
     * Sets whether errors should be batched together and send at the end of each request.
     *
     * @param bool $batchSending whether to batch together errors
     *
     * @return $this
     */
    public function setBatchSending($batchSending)
    {
        $this->config->setBatchSending($batchSending);

        return $this;
    }

    /**
     * Is batch sending is enabled?
     *
     * @return bool
     */
    public function isBatchSending()
    {
        return $this->config->isBatchSending();
    }

    /**
     * Set which release stages should be allowed to notify Bugsnag.
     *
     * Eg ['production', 'development'].
     *
     * @param string[]|null $notifyReleaseStages array of release stages to notify for
     *
     * @return $this
     */
    public function setNotifyReleaseStages(array $notifyReleaseStages = null)
    {
        $this->config->setNotifyReleaseStages($notifyReleaseStages);

        return $this;
    }

    /**
     * Should we notify Bugsnag based on the current release stage?
     *
     * @return bool
     */
    public function shouldNotify()
    {
        return $this->config->shouldNotify();
    }

    /**
     * Set the strings to filter out from metaData arrays before sending then.
     *
     * Eg. ['password', 'credit_card'].
     *
     * @deprecated Use redactedKeys instead
     *
     * @param string[] $filters an array of metaData filters
     *
     * @return $this
     */
    public function setFilters(array $filters)
    {
        $this->config->setFilters($filters);

        return $this;
    }

    /**
     * Get the array of metaData filters.
     *
     * @deprecated Use redactedKeys instead
     *
     * @return string[]
     */
    public function getFilters()
    {
        return $this->config->getFilters();
    }

    /**
     * Set the project root.
     *
     * @param string|null $projectRoot the project root path
     *
     * @return void
     */
    public function setProjectRoot($projectRoot)
    {
        $this->config->setProjectRoot($projectRoot);
    }

    /**
     * Set the project root regex.
     *
     * @param string|null $projectRootRegex the project root path
     *
     * @return void
     */
    public function setProjectRootRegex($projectRootRegex)
    {
        $this->config->setProjectRootRegex($projectRootRegex);
    }

    /**
     * Is the given file in the project?
     *
     * @param string $file
     *
     * @return bool
     */
    public function isInProject($file)
    {
        return $this->config->isInProject($file);
    }

    /**
     * Set the strip path.
     *
     * @param string|null $stripPath the absolute strip path
     *
     * @return void
     */
    public function setStripPath($stripPath)
    {
        $this->config->setStripPath($stripPath);
    }

    /**
     * Set the regular expression used to strip paths from stacktraces.
     *
     * @param string|null $stripPathRegex
     *
     * @return void
     */
    public function setStripPathRegex($stripPathRegex)
    {
        $this->config->setStripPathRegex($stripPathRegex);
    }

    /**
     * Get the stripped file path.
     *
     * @param string $file
     *
     * @return string
     */
    public function getStrippedFilePath($file)
    {
        return $this->config->getStrippedFilePath($file);
    }

    /**
     * Set if we should we send a small snippet of the code that crashed.
     *
     * This can help you diagnose even faster from within your dashboard.
     *
     * @param bool $sendCode whether to send code to Bugsnag
     *
     * @return $this
     */
    public function setSendCode($sendCode)
    {
        $this->config->setSendCode($sendCode);

        return $this;
    }

    /**
     * Should we send a small snippet of the code that crashed?
     *
     * @return bool
     */
    public function shouldSendCode()
    {
        return $this->config->shouldSendCode();
    }

    /**
     * Sets the notifier to report as to Bugsnag.
     *
     * This should only be set by other notifier libraries.
     *
     * @param string[] $notifier an array of name, version, url.
     *
     * @return $this
     */
    public function setNotifier(array $notifier)
    {
        $this->config->setNotifier($notifier);

        return $this;
    }

    /**
     * Get the notifier to report as to Bugsnag.
     *
     * @return string[]
     */
    public function getNotifier()
    {
        return $this->config->getNotifier();
    }

    /**
     * Set your app's semantic version, eg "1.2.3".
     *
     * @param string|null $appVersion the app's version
     *
     * @return $this
     */
    public function setAppVersion($appVersion)
    {
        $this->config->setAppVersion($appVersion);

        return $this;
    }

    /**
     * Set your release stage, eg "production" or "development".
     *
     * @param string|null $releaseStage the app's current release stage
     *
     * @return $this
     */
    public function setReleaseStage($releaseStage)
    {
        $this->config->setReleaseStage($releaseStage);

        return $this;
    }

    /**
     * Set the type of application executing the code.
     *
     * This is usually used to represent if you are running plain PHP code
     * "php", via a framework, eg "laravel", or executing through delayed
     * worker code, eg "resque".
     *
     * @param string|null $type the current type
     *
     * @return $this
     */
    public function setAppType($type)
    {
        $this->config->setAppType($type);

        return $this;
    }

    /**
     * Set the fallback application type.
     *
     * This is should be used only by libraries to set an fallback app type.
     *
     * @param string|null $type the fallback type
     *
     * @return $this
     */
    public function setFallbackType($type)
    {
        $this->config->setFallbackType($type);

        return $this;
    }

    /**
     * Get the application data.
     *
     * @return array
     */
    public function getAppData()
    {
        return $this->config->getAppData();
    }

    /**
     * Set the hostname.
     *
     * @param string|null $hostname the hostname
     *
     * @return $this
     */
    public function setHostname($hostname)
    {
        $this->config->setHostname($hostname);

        return $this;
    }

    /**
     * Get the device data.
     *
     * @return array
     */
    public function getDeviceData()
    {
        return $this->config->getDeviceData();
    }

    /**
     * Set custom metadata to send to Bugsnag.
     *
     * You can use this to add custom tabs of data to each error on your
     * Bugsnag dashboard.
     *
     * @param array[] $metaData an array of arrays of custom data
     * @param bool    $merge    should we merge the meta data
     *
     * @return $this
     */
    public function setMetaData(array $metaData, $merge = true)
    {
        $this->config->setMetaData($metaData, $merge);

        return $this;
    }

    /**
     * Get the custom metadata to send to Bugsnag.
     *
     * @return array[]
     */
    public function getMetaData()
    {
        return $this->config->getMetaData();
    }

    /**
     * Add a single feature flag to all future reports.
     *
     * @param string $name
     * @param string|null $variant
     *
     * @return void
     */
    public function addFeatureFlag($name, $variant = null)
    {
        $this->config->addFeatureFlag($name, $variant);
    }

    /**
     * Add multiple feature flags to all future reports.
     *
     * @param FeatureFlag[] $featureFlags
     * @phpstan-param list<FeatureFlag> $featureFlags
     *
     * @return void
     */
    public function addFeatureFlags(array $featureFlags)
    {
        $this->config->addFeatureFlags($featureFlags);
    }

    /**
     * Remove the feature flag with the given name from all future reports.
     *
     * @param string $name
     *
     * @return void
     */
    public function clearFeatureFlag($name)
    {
        $this->config->clearFeatureFlag($name);
    }

    /**
     * Remove all feature flags from all future reports.
     *
     * @return void
     */
    public function clearFeatureFlags()
    {
        $this->config->clearFeatureFlags();
    }

    /**
     * Set Bugsnag's error reporting level.
     *
     * If this is not set, we'll use your current PHP error_reporting value
     * from your ini file or error_reporting(...) calls.
     *
     * @param int|null $errorReportingLevel the error reporting level integer
     *
     * @return $this
     */
    public function setErrorReportingLevel($errorReportingLevel)
    {
        $this->config->setErrorReportingLevel($errorReportingLevel);

        return $this;
    }

    /**
     * Should we ignore the given error code?
     *
     * @param int $code the error code
     *
     * @return bool
     */
    public function shouldIgnoreErrorCode($code)
    {
        return $this->config->shouldIgnoreErrorCode($code);
    }

    /**
     * Set notification delivery endpoint.
     *
     * @param string $endpoint
     *
     * @return $this
     */
    public function setNotifyEndpoint($endpoint)
    {
        $this->config->setNotifyEndpoint($endpoint);

        return $this;
    }

    /**
     * Get notification delivery endpoint.
     *
     * @return string
     */
    public function getNotifyEndpoint()
    {
        return $this->config->getNotifyEndpoint();
    }

    /**
     * Set session delivery endpoint.
     *
     * @param string $endpoint
     *
     * @return $this
     */
    public function setSessionEndpoint($endpoint)
    {
        $this->config->setSessionEndpoint($endpoint);

        return $this;
    }

    /**
     * Get session delivery endpoint.
     *
     * @return string
     */
    public function getSessionEndpoint()
    {
        return $this->config->getSessionEndpoint();
    }

    /**
     * Set the build endpoint.
     *
     * @param string $endpoint the build endpoint
     *
     * @return $this
     */
    public function setBuildEndpoint($endpoint)
    {
        $this->config->setBuildEndpoint($endpoint);

        return $this;
    }

    /**
     * Get the build endpoint.
     *
     * @return string
     */
    public function getBuildEndpoint()
    {
        return $this->config->getBuildEndpoint();
    }

    /**
     * Set session tracking state.
     *
     * @param bool $track whether to track sessions
     *
     * @return $this
     */
    public function setAutoCaptureSessions($track)
    {
        $this->config->setAutoCaptureSessions($track);

        return $this;
    }

    /**
     * Whether should be auto-capturing sessions.
     *
     * @return bool
     */
    public function shouldCaptureSessions()
    {
        return $this->config->shouldCaptureSessions();
    }

    /**
     * Get the session client.
     *
     * @return \GuzzleHttp\ClientInterface
     *
     * @deprecated This will be removed in the next major version.
     */
    public function getSessionClient()
    {
        return $this->config->getSessionClient();
    }

    /**
     * Set the amount to increase the memory_limit when an OOM is triggered.
     *
     * This is an amount of bytes or 'null' to disable increasing the limit.
     *
     * @param int|null $value
     *
     * @return Configuration
     */
    public function setMemoryLimitIncrease($value)
    {
        return $this->config->setMemoryLimitIncrease($value);
    }

    /**
     * Get the amount to increase the memory_limit when an OOM is triggered.
     *
     * This will return 'null' if this feature is disabled.
     *
     * @return int|null
     */
    public function getMemoryLimitIncrease()
    {
        return $this->config->getMemoryLimitIncrease();
    }

    /**
     * Set the array of classes that should not be sent to Bugsnag.
     *
     * @param array $discardClasses
     *
     * @return $this
     */
    public function setDiscardClasses(array $discardClasses)
    {
        $this->config->setDiscardClasses($discardClasses);

        return $this;
    }

    /**
     * Get the array of classes that should not be sent to Bugsnag.
     *
     * This can contain both fully qualified class names and regular expressions.
     *
     * @return array
     */
    public function getDiscardClasses()
    {
        return $this->config->getDiscardClasses();
    }

    /**
     * Set the array of metadata keys that should be redacted.
     *
     * @param string[] $redactedKeys
     *
     * @return $this
     */
    public function setRedactedKeys(array $redactedKeys)
    {
        $this->config->setRedactedKeys($redactedKeys);

        return $this;
    }

    /**
     * Get the array of metadata keys that should be redacted.
     *
     * @return string[]
     */
    public function getRedactedKeys()
    {
        return $this->config->getRedactedKeys();
    }

    /**
     * @param int $maxBreadcrumbs
     *
     * @return $this
     */
    public function setMaxBreadcrumbs($maxBreadcrumbs)
    {
        $this->recorder->setMaxBreadcrumbs($maxBreadcrumbs);

        return $this;
    }

    /**
     * @return int
     */
    public function getMaxBreadcrumbs()
    {
        return $this->recorder->getMaxBreadcrumbs();
    }
}
