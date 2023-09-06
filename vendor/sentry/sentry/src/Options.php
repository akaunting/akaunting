<?php

declare(strict_types=1);

namespace Sentry;

use Sentry\Integration\ErrorListenerIntegration;
use Sentry\Integration\IntegrationInterface;
use Symfony\Component\OptionsResolver\Options as SymfonyOptions;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Configuration container for the Sentry client.
 *
 * @author Stefano Arlandini <sarlandini@alice.it>
 */
final class Options
{
    /**
     * The default maximum number of breadcrumbs that will be sent with an
     * event.
     */
    public const DEFAULT_MAX_BREADCRUMBS = 100;

    /**
     * The default maximum execution time in seconds for the request+response
     * as a whole.
     */
    public const DEFAULT_HTTP_TIMEOUT = 5;

    /**
     * The default maximum number of seconds to wait while trying to connect to a
     * server.
     */
    public const DEFAULT_HTTP_CONNECT_TIMEOUT = 2;

    /**
     * @var array<string, mixed> The configuration options
     */
    private $options;

    /**
     * @var OptionsResolver The options resolver
     */
    private $resolver;

    /**
     * Class constructor.
     *
     * @param array<string, mixed> $options The configuration options
     */
    public function __construct(array $options = [])
    {
        $this->resolver = new OptionsResolver();

        $this->configureOptions($this->resolver);

        $this->options = $this->resolver->resolve($options);

        if (true === $this->options['enable_tracing'] && null === $this->options['traces_sample_rate']) {
            $this->options = array_merge($this->options, ['traces_sample_rate' => 1]);
        }
    }

    /**
     * Gets the number of attempts to resend an event that failed to be sent.
     *
     * @deprecated since version 3.5, to be removed in 4.0
     */
    public function getSendAttempts(/*bool $triggerDeprecation = true*/): int
    {
        if (0 === \func_num_args() || false !== func_get_arg(0)) {
            @trigger_error(sprintf('Method %s() is deprecated since version 3.5 and will be removed in 4.0.', __METHOD__), \E_USER_DEPRECATED);
        }

        return $this->options['send_attempts'];
    }

    /**
     * Sets the number of attempts to resend an event that failed to be sent.
     *
     * @param int $attemptsCount The number of attempts
     *
     * @deprecated since version 3.5, to be removed in 4.0
     */
    public function setSendAttempts(int $attemptsCount): void
    {
        @trigger_error(sprintf('Method %s() is deprecated since version 3.5 and will be removed in 4.0.', __METHOD__), \E_USER_DEPRECATED);

        $options = array_merge($this->options, ['send_attempts' => $attemptsCount]);

        $this->options = $this->resolver->resolve($options);
    }

    /**
     * Gets the prefixes which should be stripped from filenames to create
     * relative paths.
     *
     * @return string[]
     */
    public function getPrefixes(): array
    {
        return $this->options['prefixes'];
    }

    /**
     * Sets the prefixes which should be stripped from filenames to create
     * relative paths.
     *
     * @param string[] $prefixes The prefixes
     */
    public function setPrefixes(array $prefixes): void
    {
        $options = array_merge($this->options, ['prefixes' => $prefixes]);

        $this->options = $this->resolver->resolve($options);
    }

    /**
     * Gets the sampling factor to apply to events. A value of 0 will deny
     * sending any events, and a value of 1 will send 100% of events.
     */
    public function getSampleRate(): float
    {
        return $this->options['sample_rate'];
    }

    /**
     * Sets the sampling factor to apply to events. A value of 0 will deny
     * sending any events, and a value of 1 will send 100% of events.
     *
     * @param float $sampleRate The sampling factor
     */
    public function setSampleRate(float $sampleRate): void
    {
        $options = array_merge($this->options, ['sample_rate' => $sampleRate]);

        $this->options = $this->resolver->resolve($options);
    }

    /**
     * Gets the sampling factor to apply to transaction. A value of 0 will deny
     * sending any transaction, and a value of 1 will send 100% of transaction.
     */
    public function getTracesSampleRate(): ?float
    {
        return $this->options['traces_sample_rate'];
    }

    /**
     * Sets if tracing should be enabled or not. If null tracesSampleRate takes
     * precedence.
     *
     * @param bool|null $enableTracing Boolean if tracing should be enabled or not
     */
    public function setEnableTracing(?bool $enableTracing): void
    {
        $options = array_merge($this->options, ['enable_tracing' => $enableTracing]);

        $this->options = $this->resolver->resolve($options);
    }

    /**
     * Gets if tracing is enabled or not.
     *
     * @return bool|null If the option `enable_tracing` is set or not
     */
    public function getEnableTracing(): ?bool
    {
        return $this->options['enable_tracing'];
    }

    /**
     * Sets the sampling factor to apply to transactions. A value of 0 will deny
     * sending any transactions, and a value of 1 will send 100% of transactions.
     *
     * @param ?float $sampleRate The sampling factor
     */
    public function setTracesSampleRate(?float $sampleRate): void
    {
        $options = array_merge($this->options, ['traces_sample_rate' => $sampleRate]);

        $this->options = $this->resolver->resolve($options);
    }

    public function getProfilesSampleRate(): ?float
    {
        /** @var int|float|null $value */
        $value = $this->options['profiles_sample_rate'] ?? null;

        return $value ?? null;
    }

    public function setProfilesSampleRate(?float $sampleRate): void
    {
        $options = array_merge($this->options, ['profiles_sample_rate' => $sampleRate]);

        $this->options = $this->resolver->resolve($options);
    }

    /**
     * Gets whether tracing is enabled or not. The feature is enabled when at
     * least one of the `traces_sample_rate` and `traces_sampler` options is
     * set and `enable_tracing` is set and not false.
     */
    public function isTracingEnabled(): bool
    {
        if (null !== $this->getEnableTracing() && false === $this->getEnableTracing()) {
            return false;
        }

        return null !== $this->getTracesSampleRate() || null !== $this->getTracesSampler();
    }

    /**
     * Gets whether the stacktrace will be attached on captureMessage.
     */
    public function shouldAttachStacktrace(): bool
    {
        return $this->options['attach_stacktrace'];
    }

    /**
     * Sets whether the stacktrace will be attached on captureMessage.
     *
     * @param bool $enable Flag indicating if the stacktrace will be attached to captureMessage calls
     */
    public function setAttachStacktrace(bool $enable): void
    {
        $options = array_merge($this->options, ['attach_stacktrace' => $enable]);

        $this->options = $this->resolver->resolve($options);
    }

    /**
     * Gets the number of lines of code context to capture, or null if none.
     */
    public function getContextLines(): ?int
    {
        return $this->options['context_lines'];
    }

    /**
     * Sets the number of lines of code context to capture, or null if none.
     *
     * @param int|null $contextLines The number of lines of code
     */
    public function setContextLines(?int $contextLines): void
    {
        $options = array_merge($this->options, ['context_lines' => $contextLines]);

        $this->options = $this->resolver->resolve($options);
    }

    /**
     * Returns whether the requests should be compressed using GZIP or not.
     */
    public function isCompressionEnabled(): bool
    {
        return $this->options['enable_compression'];
    }

    /**
     * Sets whether the request should be compressed using JSON or not.
     *
     * @param bool $enabled Flag indicating whether the request should be compressed
     */
    public function setEnableCompression(bool $enabled): void
    {
        $options = array_merge($this->options, ['enable_compression' => $enabled]);

        $this->options = $this->resolver->resolve($options);
    }

    /**
     * Gets the environment.
     */
    public function getEnvironment(): ?string
    {
        return $this->options['environment'];
    }

    /**
     * Sets the environment.
     *
     * @param string|null $environment The environment
     */
    public function setEnvironment(?string $environment): void
    {
        $options = array_merge($this->options, ['environment' => $environment]);

        $this->options = $this->resolver->resolve($options);
    }

    /**
     * Gets the list of paths to exclude from in_app detection.
     *
     * @return string[]
     */
    public function getInAppExcludedPaths(): array
    {
        return $this->options['in_app_exclude'];
    }

    /**
     * Sets the list of paths to exclude from in_app detection.
     *
     * @param string[] $paths The list of paths
     */
    public function setInAppExcludedPaths(array $paths): void
    {
        $options = array_merge($this->options, ['in_app_exclude' => $paths]);

        $this->options = $this->resolver->resolve($options);
    }

    /**
     * Gets the list of paths which has to be identified as in_app.
     *
     * @return string[]
     */
    public function getInAppIncludedPaths(): array
    {
        return $this->options['in_app_include'];
    }

    /**
     * Set the list of paths to include in in_app detection.
     *
     * @param string[] $paths The list of paths
     */
    public function setInAppIncludedPaths(array $paths): void
    {
        $options = array_merge($this->options, ['in_app_include' => $paths]);

        $this->options = $this->resolver->resolve($options);
    }

    /**
     * Gets the logger used by Sentry.
     *
     * @deprecated since version 3.2, to be removed in 4.0
     */
    public function getLogger(/*bool $triggerDeprecation = true*/): string
    {
        if (0 === \func_num_args() || false !== func_get_arg(0)) {
            @trigger_error(sprintf('Method %s() is deprecated since version 3.2 and will be removed in 4.0.', __METHOD__), \E_USER_DEPRECATED);
        }

        return $this->options['logger'];
    }

    /**
     * Sets the logger used by Sentry.
     *
     * @param string $logger The logger
     *
     * @deprecated since version 3.2, to be removed in 4.0
     */
    public function setLogger(string $logger): void
    {
        @trigger_error(sprintf('Method %s() is deprecated since version 3.2 and will be removed in 4.0.', __METHOD__), \E_USER_DEPRECATED);

        $options = array_merge($this->options, ['logger' => $logger]);

        $this->options = $this->resolver->resolve($options);
    }

    /**
     * Gets the release tag to be passed with every event sent to Sentry.
     *
     * @return string
     */
    public function getRelease(): ?string
    {
        return $this->options['release'];
    }

    /**
     * Sets the release tag to be passed with every event sent to Sentry.
     *
     * @param string|null $release The release
     */
    public function setRelease(?string $release): void
    {
        $options = array_merge($this->options, ['release' => $release]);

        $this->options = $this->resolver->resolve($options);
    }

    /**
     * Gets the DSN of the Sentry server the authenticated user is bound to.
     */
    public function getDsn(): ?Dsn
    {
        return $this->options['dsn'];
    }

    /**
     * Gets the name of the server the SDK is running on (e.g. the hostname).
     */
    public function getServerName(): string
    {
        return $this->options['server_name'];
    }

    /**
     * Sets the name of the server the SDK is running on (e.g. the hostname).
     *
     * @param string $serverName The server name
     */
    public function setServerName(string $serverName): void
    {
        $options = array_merge($this->options, ['server_name' => $serverName]);

        $this->options = $this->resolver->resolve($options);
    }

    /**
     * Gets a list of exceptions to be ignored and not sent to Sentry.
     *
     * @return string[]
     */
    public function getIgnoreExceptions(): array
    {
        return $this->options['ignore_exceptions'];
    }

    /**
     * Sets a list of exceptions to be ignored and not sent to Sentry.
     *
     * @param string[] $ignoreErrors The list of exceptions to be ignored
     */
    public function setIgnoreExceptions(array $ignoreErrors): void
    {
        $options = array_merge($this->options, ['ignore_exceptions' => $ignoreErrors]);

        $this->options = $this->resolver->resolve($options);
    }

    /**
     * Gets a list of transaction names to be ignored and not sent to Sentry.
     *
     * @return string[]
     */
    public function getIgnoreTransactions(): array
    {
        return $this->options['ignore_transactions'];
    }

    /**
     * Sets a list of transaction names to be ignored and not sent to Sentry.
     *
     * @param string[] $ignoreTransaction The list of transaction names to be ignored
     */
    public function setIgnoreTransactions(array $ignoreTransaction): void
    {
        $options = array_merge($this->options, ['ignore_transactions' => $ignoreTransaction]);

        $this->options = $this->resolver->resolve($options);
    }

    /**
     * Gets a callback that will be invoked before an event is sent to the server.
     * If `null` is returned it won't be sent.
     *
     * @psalm-return callable(Event, ?EventHint): ?Event
     */
    public function getBeforeSendCallback(): callable
    {
        return $this->options['before_send'];
    }

    /**
     * Sets a callable to be called to decide whether an event should
     * be captured or not.
     *
     * @param callable $callback The callable
     *
     * @psalm-param callable(Event, ?EventHint): ?Event $callback
     */
    public function setBeforeSendCallback(callable $callback): void
    {
        $options = array_merge($this->options, ['before_send' => $callback]);

        $this->options = $this->resolver->resolve($options);
    }

    /**
     * Gets a callback that will be invoked before an transaction is sent to the server.
     * If `null` is returned it won't be sent.
     *
     * @psalm-return callable(Event, ?EventHint): ?Event
     */
    public function getBeforeSendTransactionCallback(): callable
    {
        return $this->options['before_send_transaction'];
    }

    /**
     * Sets a callable to be called to decide whether an transaction should
     * be captured or not.
     *
     * @param callable $callback The callable
     *
     * @psalm-param callable(Event, ?EventHint): ?Event $callback
     */
    public function setBeforeSendTransactionCallback(callable $callback): void
    {
        $options = array_merge($this->options, ['before_send_transaction' => $callback]);

        $this->options = $this->resolver->resolve($options);
    }

    /**
     * Gets an allow list of trace propagation targets.
     *
     * @return string[]|null
     */
    public function getTracePropagationTargets(): ?array
    {
        return $this->options['trace_propagation_targets'];
    }

    /**
     * Set an allow list of trace propagation targets.
     *
     * @param string[] $tracePropagationTargets Trace propagation targets
     */
    public function setTracePropagationTargets(array $tracePropagationTargets): void
    {
        $options = array_merge($this->options, ['trace_propagation_targets' => $tracePropagationTargets]);

        $this->options = $this->resolver->resolve($options);
    }

    /**
     * Gets a list of default tags for events.
     *
     * @return array<string, string>
     */
    public function getTags(): array
    {
        return $this->options['tags'];
    }

    /**
     * Sets a list of default tags for events.
     *
     * @param array<string, string> $tags A list of tags
     */
    public function setTags(array $tags): void
    {
        $options = array_merge($this->options, ['tags' => $tags]);

        $this->options = $this->resolver->resolve($options);
    }

    /**
     * Gets a bit mask for error_reporting used in {@link ErrorListenerIntegration} to filter which errors to report.
     */
    public function getErrorTypes(): int
    {
        return $this->options['error_types'] ?? error_reporting();
    }

    /**
     * Sets a bit mask for error_reporting used in {@link ErrorListenerIntegration} to filter which errors to report.
     *
     * @param int $errorTypes The bit mask
     */
    public function setErrorTypes(int $errorTypes): void
    {
        $options = array_merge($this->options, ['error_types' => $errorTypes]);

        $this->options = $this->resolver->resolve($options);
    }

    /**
     * Gets the maximum number of breadcrumbs sent with events.
     */
    public function getMaxBreadcrumbs(): int
    {
        return $this->options['max_breadcrumbs'];
    }

    /**
     * Sets the maximum number of breadcrumbs sent with events.
     *
     * @param int $maxBreadcrumbs The maximum number of breadcrumbs
     */
    public function setMaxBreadcrumbs(int $maxBreadcrumbs): void
    {
        $options = array_merge($this->options, ['max_breadcrumbs' => $maxBreadcrumbs]);

        $this->options = $this->resolver->resolve($options);
    }

    /**
     * Gets a callback that will be invoked when adding a breadcrumb.
     *
     * @psalm-return callable(Breadcrumb): ?Breadcrumb
     */
    public function getBeforeBreadcrumbCallback(): callable
    {
        return $this->options['before_breadcrumb'];
    }

    /**
     * Sets a callback that will be invoked when adding a breadcrumb, allowing
     * to optionally modify it before adding it to future events. Note that you
     * must return a valid breadcrumb from this callback. If you do not wish to
     * modify the breadcrumb, simply return it at the end. Returning `null` will
     * cause the breadcrumb to be dropped.
     *
     * @param callable $callback The callback
     *
     * @psalm-param callable(Breadcrumb): ?Breadcrumb $callback
     */
    public function setBeforeBreadcrumbCallback(callable $callback): void
    {
        $options = array_merge($this->options, ['before_breadcrumb' => $callback]);

        $this->options = $this->resolver->resolve($options);
    }

    /**
     * Sets the list of integrations that should be installed after SDK was
     * initialized or a function that receives default integrations and returns
     * a new, updated list.
     *
     * @param IntegrationInterface[]|callable(IntegrationInterface[]): IntegrationInterface[] $integrations The list or callable
     */
    public function setIntegrations($integrations): void
    {
        $options = array_merge($this->options, ['integrations' => $integrations]);

        $this->options = $this->resolver->resolve($options);
    }

    /**
     * Returns all configured integrations that will be used by the Client.
     *
     * @return IntegrationInterface[]|callable(IntegrationInterface[]): IntegrationInterface[]
     */
    public function getIntegrations()
    {
        return $this->options['integrations'];
    }

    /**
     * Should default PII be sent by default.
     */
    public function shouldSendDefaultPii(): bool
    {
        return $this->options['send_default_pii'];
    }

    /**
     * Sets if default PII should be sent with every event (if possible).
     *
     * @param bool $enable Flag indicating if default PII will be sent
     */
    public function setSendDefaultPii(bool $enable): void
    {
        $options = array_merge($this->options, ['send_default_pii' => $enable]);

        $this->options = $this->resolver->resolve($options);
    }

    /**
     * Returns whether the default integrations are enabled.
     */
    public function hasDefaultIntegrations(): bool
    {
        return $this->options['default_integrations'];
    }

    /**
     * Sets whether the default integrations are enabled.
     *
     * @param bool $enable Flag indicating whether the default integrations should be enabled
     */
    public function setDefaultIntegrations(bool $enable): void
    {
        $options = array_merge($this->options, ['default_integrations' => $enable]);

        $this->options = $this->resolver->resolve($options);
    }

    /**
     * Gets the max length for values in the event payload.
     */
    public function getMaxValueLength(): int
    {
        return $this->options['max_value_length'];
    }

    /**
     * Sets the max length for specific values in the event payload.
     *
     * @param int $maxValueLength The number of characters after which the values containing text will be truncated
     */
    public function setMaxValueLength(int $maxValueLength): void
    {
        $options = array_merge($this->options, ['max_value_length' => $maxValueLength]);

        $this->options = $this->resolver->resolve($options);
    }

    /**
     * Gets the http proxy setting.
     */
    public function getHttpProxy(): ?string
    {
        return $this->options['http_proxy'];
    }

    /**
     * Sets the http proxy. Be aware this option only works when curl client is used.
     *
     * @param string|null $httpProxy The http proxy
     */
    public function setHttpProxy(?string $httpProxy): void
    {
        $options = array_merge($this->options, ['http_proxy' => $httpProxy]);

        $this->options = $this->resolver->resolve($options);
    }

    /**
     * Gets the maximum number of seconds to wait while trying to connect to a server.
     */
    public function getHttpConnectTimeout(): float
    {
        return $this->options['http_connect_timeout'];
    }

    /**
     * Sets the maximum number of seconds to wait while trying to connect to a server.
     *
     * @param float $httpConnectTimeout The amount of time in seconds
     */
    public function setHttpConnectTimeout(float $httpConnectTimeout): void
    {
        $options = array_merge($this->options, ['http_connect_timeout' => $httpConnectTimeout]);

        $this->options = $this->resolver->resolve($options);
    }

    /**
     * Gets the maximum execution time for the request+response as a whole.
     */
    public function getHttpTimeout(): float
    {
        return $this->options['http_timeout'];
    }

    /**
     * Sets the maximum execution time for the request+response as a whole. The
     * value should also include the time for the connect phase, so it should be
     * greater than the value set for the `http_connect_timeout` option.
     *
     * @param float $httpTimeout The amount of time in seconds
     */
    public function setHttpTimeout(float $httpTimeout): void
    {
        $options = array_merge($this->options, ['http_timeout' => $httpTimeout]);

        $this->options = $this->resolver->resolve($options);
    }

    /**
     * Gets whether the silenced errors should be captured or not.
     *
     * @return bool If true, errors silenced through the @ operator will be reported,
     *              ignored otherwise
     */
    public function shouldCaptureSilencedErrors(): bool
    {
        return $this->options['capture_silenced_errors'];
    }

    /**
     * Sets whether the silenced errors should be captured or not.
     *
     * @param bool $shouldCapture If set to true, errors silenced through the @
     *                            operator will be reported, ignored otherwise
     */
    public function setCaptureSilencedErrors(bool $shouldCapture): void
    {
        $options = array_merge($this->options, ['capture_silenced_errors' => $shouldCapture]);

        $this->options = $this->resolver->resolve($options);
    }

    /**
     * Gets the limit up to which integrations should capture the HTTP request
     * body.
     */
    public function getMaxRequestBodySize(): string
    {
        return $this->options['max_request_body_size'];
    }

    /**
     * Sets the limit up to which integrations should capture the HTTP request
     * body.
     *
     * @param string $maxRequestBodySize The limit up to which request body are
     *                                   captured. It can be set to one of the
     *                                   following values:
     *
     *                                    - none: request bodies are never sent
     *                                    - small: only small request bodies will
     *                                      be captured where the cutoff for small
     *                                      depends on the SDK (typically 4KB)
     *                                    - medium: medium-sized requests and small
     *                                      requests will be captured. (typically 10KB)
     *                                    - always: the SDK will always capture the
     *                                      request body for as long as sentry can
     *                                      make sense of it
     */
    public function setMaxRequestBodySize(string $maxRequestBodySize): void
    {
        $options = array_merge($this->options, ['max_request_body_size' => $maxRequestBodySize]);

        $this->options = $this->resolver->resolve($options);
    }

    /**
     * Gets the callbacks used to customize how objects are serialized in the payload
     * of the event.
     *
     * @return array<string, callable>
     */
    public function getClassSerializers(): array
    {
        return $this->options['class_serializers'];
    }

    /**
     * Sets a list of callables that will be called to customize how objects are
     * serialized in the event's payload. The list must be a map of FQCN/callable
     * pairs.
     *
     * @param array<string, callable> $serializers The list of serializer callbacks
     */
    public function setClassSerializers(array $serializers): void
    {
        $options = array_merge($this->options, ['class_serializers' => $serializers]);

        $this->options = $this->resolver->resolve($options);
    }

    /**
     * Gets a callback that will be invoked when we sample a Transaction.
     *
     * @psalm-return null|callable(\Sentry\Tracing\SamplingContext): float
     */
    public function getTracesSampler(): ?callable
    {
        return $this->options['traces_sampler'];
    }

    /**
     * Sets a callback that will be invoked when we take the sampling decision for Transactions.
     * Return a number between 0 and 1 to define the sample rate for the provided SamplingContext.
     *
     * @param ?callable $sampler The sampler
     *
     * @psalm-param null|callable(\Sentry\Tracing\SamplingContext): float $sampler
     */
    public function setTracesSampler(?callable $sampler): void
    {
        $options = array_merge($this->options, ['traces_sampler' => $sampler]);

        $this->options = $this->resolver->resolve($options);
    }

    /**
     * Configures the options of the client.
     *
     * @param OptionsResolver $resolver The resolver for the options
     *
     * @throws \Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException
     * @throws \Symfony\Component\OptionsResolver\Exception\AccessException
     */
    private function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'integrations' => [],
            'default_integrations' => true,
            'send_attempts' => 0,
            'prefixes' => array_filter(explode(\PATH_SEPARATOR, get_include_path() ?: '')),
            'sample_rate' => 1,
            'enable_tracing' => null,
            'traces_sample_rate' => null,
            'traces_sampler' => null,
            'profiles_sample_rate' => null,
            'attach_stacktrace' => false,
            'context_lines' => 5,
            'enable_compression' => true,
            'environment' => $_SERVER['SENTRY_ENVIRONMENT'] ?? null,
            'logger' => 'php',
            'release' => $_SERVER['SENTRY_RELEASE'] ?? null,
            'dsn' => $_SERVER['SENTRY_DSN'] ?? null,
            'server_name' => gethostname(),
            'ignore_exceptions' => [],
            'ignore_transactions' => [],
            'before_send' => static function (Event $event): Event {
                return $event;
            },
            'before_send_transaction' => static function (Event $transaction): Event {
                return $transaction;
            },
            'trace_propagation_targets' => [],
            'tags' => [],
            'error_types' => null,
            'max_breadcrumbs' => self::DEFAULT_MAX_BREADCRUMBS,
            'before_breadcrumb' => static function (Breadcrumb $breadcrumb): Breadcrumb {
                return $breadcrumb;
            },
            'in_app_exclude' => [],
            'in_app_include' => [],
            'send_default_pii' => false,
            'max_value_length' => 1024,
            'http_proxy' => null,
            'http_connect_timeout' => self::DEFAULT_HTTP_CONNECT_TIMEOUT,
            'http_timeout' => self::DEFAULT_HTTP_TIMEOUT,
            'capture_silenced_errors' => false,
            'max_request_body_size' => 'medium',
            'class_serializers' => [],
        ]);

        $resolver->setAllowedTypes('send_attempts', 'int');
        $resolver->setAllowedTypes('prefixes', 'string[]');
        $resolver->setAllowedTypes('sample_rate', ['int', 'float']);
        $resolver->setAllowedTypes('enable_tracing', ['null', 'bool']);
        $resolver->setAllowedTypes('traces_sample_rate', ['null', 'int', 'float']);
        $resolver->setAllowedTypes('traces_sampler', ['null', 'callable']);
        $resolver->setAllowedTypes('profiles_sample_rate', ['null', 'int', 'float']);
        $resolver->setAllowedTypes('attach_stacktrace', 'bool');
        $resolver->setAllowedTypes('context_lines', ['null', 'int']);
        $resolver->setAllowedTypes('enable_compression', 'bool');
        $resolver->setAllowedTypes('environment', ['null', 'string']);
        $resolver->setAllowedTypes('in_app_exclude', 'string[]');
        $resolver->setAllowedTypes('in_app_include', 'string[]');
        $resolver->setAllowedTypes('logger', ['null', 'string']);
        $resolver->setAllowedTypes('release', ['null', 'string']);
        $resolver->setAllowedTypes('dsn', ['null', 'string', 'bool', Dsn::class]);
        $resolver->setAllowedTypes('server_name', 'string');
        $resolver->setAllowedTypes('before_send', ['callable']);
        $resolver->setAllowedTypes('before_send_transaction', ['callable']);
        $resolver->setAllowedTypes('ignore_exceptions', 'string[]');
        $resolver->setAllowedTypes('ignore_transactions', 'string[]');
        $resolver->setAllowedTypes('trace_propagation_targets', ['null', 'string[]']);
        $resolver->setAllowedTypes('tags', 'string[]');
        $resolver->setAllowedTypes('error_types', ['null', 'int']);
        $resolver->setAllowedTypes('max_breadcrumbs', 'int');
        $resolver->setAllowedTypes('before_breadcrumb', ['callable']);
        $resolver->setAllowedTypes('integrations', ['Sentry\\Integration\\IntegrationInterface[]', 'callable']);
        $resolver->setAllowedTypes('send_default_pii', 'bool');
        $resolver->setAllowedTypes('default_integrations', 'bool');
        $resolver->setAllowedTypes('max_value_length', 'int');
        $resolver->setAllowedTypes('http_proxy', ['null', 'string']);
        $resolver->setAllowedTypes('http_connect_timeout', ['int', 'float']);
        $resolver->setAllowedTypes('http_timeout', ['int', 'float']);
        $resolver->setAllowedTypes('capture_silenced_errors', 'bool');
        $resolver->setAllowedTypes('max_request_body_size', 'string');
        $resolver->setAllowedTypes('class_serializers', 'array');

        $resolver->setAllowedValues('max_request_body_size', ['none', 'never', 'small', 'medium', 'always']);
        $resolver->setAllowedValues('dsn', \Closure::fromCallable([$this, 'validateDsnOption']));
        $resolver->setAllowedValues('max_breadcrumbs', \Closure::fromCallable([$this, 'validateMaxBreadcrumbsOptions']));
        $resolver->setAllowedValues('class_serializers', \Closure::fromCallable([$this, 'validateClassSerializersOption']));
        $resolver->setAllowedValues('context_lines', \Closure::fromCallable([$this, 'validateContextLinesOption']));

        $resolver->setNormalizer('dsn', \Closure::fromCallable([$this, 'normalizeDsnOption']));
        $resolver->setNormalizer('tags', static function (SymfonyOptions $options, array $value): array {
            if (!empty($value)) {
                @trigger_error('The option "tags" is deprecated since version 3.2 and will be removed in 4.0. Either set the tags on the scope or on the event.', \E_USER_DEPRECATED);
            }

            return $value;
        });

        $resolver->setNormalizer('prefixes', function (SymfonyOptions $options, array $value) {
            return array_map([$this, 'normalizeAbsolutePath'], $value);
        });

        $resolver->setNormalizer('in_app_exclude', function (SymfonyOptions $options, array $value) {
            return array_map([$this, 'normalizeAbsolutePath'], $value);
        });

        $resolver->setNormalizer('in_app_include', function (SymfonyOptions $options, array $value) {
            return array_map([$this, 'normalizeAbsolutePath'], $value);
        });

        $resolver->setNormalizer('logger', function (SymfonyOptions $options, ?string $value): ?string {
            if ('php' !== $value) {
                @trigger_error('The option "logger" is deprecated.', \E_USER_DEPRECATED);
            }

            return $value;
        });
    }

    /**
     * Normalizes the given path as an absolute path.
     *
     * @param string $value The path
     */
    private function normalizeAbsolutePath(string $value): string
    {
        $path = @realpath($value);

        if (false === $path) {
            $path = $value;
        }

        return $path;
    }

    /**
     * Normalizes the DSN option by parsing the host, public and secret keys and
     * an optional path.
     *
     * @param SymfonyOptions       $options The configuration options
     * @param string|bool|Dsn|null $value   The actual value of the option to normalize
     */
    private function normalizeDsnOption(SymfonyOptions $options, $value): ?Dsn
    {
        if (null === $value || \is_bool($value)) {
            return null;
        }

        if ($value instanceof Dsn) {
            return $value;
        }

        switch (strtolower($value)) {
            case '':
            case 'false':
            case '(false)':
            case 'empty':
            case '(empty)':
            case 'null':
            case '(null)':
                return null;
        }

        return Dsn::createFromString($value);
    }

    /**
     * Validates the DSN option ensuring that all required pieces are set and
     * that the URL is valid.
     *
     * @param string|bool|Dsn|null $dsn The value of the option
     */
    private function validateDsnOption($dsn): bool
    {
        if (null === $dsn || $dsn instanceof Dsn) {
            return true;
        }

        if (\is_bool($dsn)) {
            return false === $dsn;
        }

        switch (strtolower($dsn)) {
            case '':
            case 'false':
            case '(false)':
            case 'empty':
            case '(empty)':
            case 'null':
            case '(null)':
                return true;
        }

        try {
            Dsn::createFromString($dsn);

            return true;
        } catch (\InvalidArgumentException $exception) {
            return false;
        }
    }

    /**
     * Validates if the value of the max_breadcrumbs option is in range.
     *
     * @param int $value The value to validate
     */
    private function validateMaxBreadcrumbsOptions(int $value): bool
    {
        return $value >= 0 && $value <= self::DEFAULT_MAX_BREADCRUMBS;
    }

    /**
     * Validates that the values passed to the `class_serializers` option are valid.
     *
     * @param mixed[] $serializers The value to validate
     */
    private function validateClassSerializersOption(array $serializers): bool
    {
        foreach ($serializers as $class => $serializer) {
            if (!\is_string($class) || !\is_callable($serializer)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Validates that the value passed to the "context_lines" option is valid.
     *
     * @param int|null $contextLines The value to validate
     */
    private function validateContextLinesOption(?int $contextLines): bool
    {
        return null === $contextLines || $contextLines >= 0;
    }
}
