<?php

declare(strict_types=1);

namespace Sentry;

use Sentry\Context\OsContext;
use Sentry\Context\RuntimeContext;
use Sentry\Profiling\Profile;
use Sentry\Tracing\Span;

/**
 * This is the base class for classes containing event data.
 *
 * @author Stefano Arlandini <sarlandini@alice.it>
 */
final class Event
{
    public const DEFAULT_ENVIRONMENT = 'production';

    /**
     * @var EventId The ID
     */
    private $id;

    /**
     * @var float|null The date and time of when this event was generated
     */
    private $timestamp;

    /**
     * This property is used if it's a Transaction event together with $timestamp it's the duration of the transaction.
     *
     * @var float|null The date and time of when this event was generated
     */
    private $startTimestamp;

    /**
     * @var Severity|null The severity of this event
     */
    private $level;

    /**
     * @var string|null The name of the logger which created the record
     */
    private $logger;

    /**
     * @var string|null the name of the transaction (or culprit) which caused this exception
     */
    private $transaction;

    /**
     * @var CheckIn|null The check in data
     */
    private $checkIn;

    /**
     * @var string|null The name of the server (e.g. the host name)
     */
    private $serverName;

    /**
     * @var string|null The release of the program
     */
    private $release;

    /**
     * @var string|null The error message
     */
    private $message;

    /**
     * @var string|null The formatted error message
     */
    private $messageFormatted;

    /**
     * @var string[] The parameters to use to format the message
     */
    private $messageParams = [];

    /**
     * @var string|null The environment where this event generated (e.g. production)
     */
    private $environment;

    /**
     * @var array<string, string> A list of relevant modules and their versions
     */
    private $modules = [];

    /**
     * @var array<string, mixed> The request data
     */
    private $request = [];

    /**
     * @var array<string, string> A list of tags associated to this event
     */
    private $tags = [];

    /**
     * @var OsContext|null The server OS context data
     */
    private $osContext;

    /**
     * @var RuntimeContext|null The runtime context data
     */
    private $runtimeContext;

    /**
     * @var UserDataBag|null The user context data
     */
    private $user;

    /**
     * @var array<string, array<string, mixed>> An arbitrary mapping of additional contexts associated to this event
     */
    private $contexts = [];

    /**
     * @var array<string, mixed> An arbitrary mapping of additional metadata
     */
    private $extra = [];

    /**
     * @var string[] An array of strings used to dictate the deduplication of this event
     */
    private $fingerprint = [];

    /**
     * @var Breadcrumb[] The associated breadcrumbs
     */
    private $breadcrumbs = [];

    /**
     * @var Span[] The array of spans if it's a transaction
     */
    private $spans = [];

    /**
     * @var ExceptionDataBag[] The exceptions
     */
    private $exceptions = [];

    /**
     * @var Stacktrace|null The stacktrace that generated this event
     */
    private $stacktrace;

    /**
     * A place to stash data which is needed at some point in the SDK's
     * event processing pipeline but which shouldn't get sent to Sentry.
     *
     * @var array<string, mixed>
     */
    private $sdkMetadata = [];

    /**
     * @var string The Sentry SDK identifier
     */
    private $sdkIdentifier = Client::SDK_IDENTIFIER;

    /**
     * @var string The Sentry SDK version
     */
    private $sdkVersion = Client::SDK_VERSION;

    /**
     * @var EventType The type of the Event
     */
    private $type;

    /**
     * @var Profile|null The profile data
     */
    private $profile;

    private function __construct(?EventId $eventId, EventType $eventType)
    {
        $this->id = $eventId ?? EventId::generate();
        $this->timestamp = microtime(true);
        $this->type = $eventType;
    }

    /**
     * Creates a new event.
     *
     * @param EventId|null $eventId The ID of the event
     */
    public static function createEvent(?EventId $eventId = null): self
    {
        return new self($eventId, EventType::event());
    }

    /**
     * Creates a new transaction event.
     *
     * @param EventId|null $eventId The ID of the event
     */
    public static function createTransaction(EventId $eventId = null): self
    {
        return new self($eventId, EventType::transaction());
    }

    public static function createCheckIn(?EventId $eventId = null): self
    {
        return new self($eventId, EventType::checkIn());
    }

    /**
     * Gets the ID of this event.
     */
    public function getId(): EventId
    {
        return $this->id;
    }

    /**
     * Gets the identifier of the SDK package that generated this event.
     *
     * @internal
     */
    public function getSdkIdentifier(): string
    {
        return $this->sdkIdentifier;
    }

    /**
     * Sets the identifier of the SDK package that generated this event.
     *
     * @internal
     */
    public function setSdkIdentifier(string $sdkIdentifier): void
    {
        $this->sdkIdentifier = $sdkIdentifier;
    }

    /**
     * Gets the version of the SDK package that generated this Event.
     *
     * @internal
     */
    public function getSdkVersion(): string
    {
        return $this->sdkVersion;
    }

    /**
     * Sets the version of the SDK package that generated this Event.
     *
     * @internal
     */
    public function setSdkVersion(string $sdkVersion): void
    {
        $this->sdkVersion = $sdkVersion;
    }

    /**
     * Gets the timestamp of when this event was generated.
     *
     * @return float
     */
    public function getTimestamp(): ?float
    {
        return $this->timestamp;
    }

    /**
     * Sets the timestamp of when the Event was created.
     */
    public function setTimestamp(?float $timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    /**
     * Gets the severity of this event.
     */
    public function getLevel(): ?Severity
    {
        return $this->level;
    }

    /**
     * Sets the severity of this event.
     *
     * @param Severity|null $level The severity
     */
    public function setLevel(?Severity $level): void
    {
        $this->level = $level;
    }

    /**
     * Gets the name of the logger which created the event.
     */
    public function getLogger(): ?string
    {
        return $this->logger;
    }

    /**
     * Sets the name of the logger which created the event.
     *
     * @param string|null $logger The logger name
     */
    public function setLogger(?string $logger): void
    {
        $this->logger = $logger;
    }

    /**
     * Gets the name of the transaction (or culprit) which caused this
     * exception.
     */
    public function getTransaction(): ?string
    {
        return $this->transaction;
    }

    /**
     * Sets the name of the transaction (or culprit) which caused this
     * exception.
     *
     * @param string|null $transaction The transaction name
     */
    public function setTransaction(?string $transaction): void
    {
        $this->transaction = $transaction;
    }

    public function setCheckIn(?CheckIn $checkIn): void
    {
        $this->checkIn = $checkIn;
    }

    public function getCheckIn(): ?CheckIn
    {
        return $this->checkIn;
    }

    /**
     * Gets the name of the server.
     */
    public function getServerName(): ?string
    {
        return $this->serverName;
    }

    /**
     * Sets the name of the server.
     *
     * @param string|null $serverName The server name
     */
    public function setServerName(?string $serverName): void
    {
        $this->serverName = $serverName;
    }

    /**
     * Gets the release of the program.
     */
    public function getRelease(): ?string
    {
        return $this->release;
    }

    /**
     * Sets the release of the program.
     *
     * @param string|null $release The release
     */
    public function setRelease(?string $release): void
    {
        $this->release = $release;
    }

    /**
     * Gets the error message.
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * Gets the formatted message.
     */
    public function getMessageFormatted(): ?string
    {
        return $this->messageFormatted;
    }

    /**
     * Gets the parameters to use to format the message.
     *
     * @return string[]
     */
    public function getMessageParams(): array
    {
        return $this->messageParams;
    }

    /**
     * Sets the error message.
     *
     * @param string      $message   The message
     * @param string[]    $params    The parameters to use to format the message
     * @param string|null $formatted The formatted message
     */
    public function setMessage(string $message, array $params = [], ?string $formatted = null): void
    {
        $this->message = $message;
        $this->messageParams = $params;
        $this->messageFormatted = $formatted;
    }

    /**
     * Gets a list of relevant modules and their versions.
     *
     * @return array<string, string>
     */
    public function getModules(): array
    {
        return $this->modules;
    }

    /**
     * Sets a list of relevant modules and their versions.
     *
     * @param array<string, string> $modules
     */
    public function setModules(array $modules): void
    {
        $this->modules = $modules;
    }

    /**
     * Gets the request data.
     *
     * @return array<string, mixed>
     */
    public function getRequest(): array
    {
        return $this->request;
    }

    /**
     * Sets the request data.
     *
     * @param array<string, mixed> $request The request data
     */
    public function setRequest(array $request): void
    {
        $this->request = $request;
    }

    /**
     * Gets an arbitrary mapping of additional contexts.
     *
     * @return array<string, array<string, mixed>>
     */
    public function getContexts(): array
    {
        return $this->contexts;
    }

    /**
     * Sets data to the context by a given name.
     *
     * @param string               $name The name that uniquely identifies the context
     * @param array<string, mixed> $data The data of the context
     */
    public function setContext(string $name, array $data): self
    {
        if (!empty($data)) {
            $this->contexts[$name] = $data;
        }

        return $this;
    }

    /**
     * Gets an arbitrary mapping of additional metadata.
     *
     * @return array<string, mixed>
     */
    public function getExtra(): array
    {
        return $this->extra;
    }

    /**
     * Sets an arbitrary mapping of additional metadata.
     *
     * @param array<string, mixed> $extra The context object
     */
    public function setExtra(array $extra): void
    {
        $this->extra = $extra;
    }

    /**
     * Gets a list of tags associated to this event.
     *
     * @return array<string, string>
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * Sets a list of tags associated to this event.
     *
     * @param array<string, string> $tags The tags to set
     */
    public function setTags(array $tags): void
    {
        $this->tags = $tags;
    }

    /**
     * Sets or updates a tag in this event.
     *
     * @param string $key   The key that uniquely identifies the tag
     * @param string $value The value
     */
    public function setTag(string $key, string $value): void
    {
        $this->tags[$key] = $value;
    }

    /**
     * Removes a given tag from the event.
     *
     * @param string $key The key that uniquely identifies the tag
     */
    public function removeTag(string $key): void
    {
        unset($this->tags[$key]);
    }

    /**
     * Gets the user context.
     */
    public function getUser(): ?UserDataBag
    {
        return $this->user;
    }

    /**
     * Sets the user context.
     *
     * @param UserDataBag|null $user The context object
     */
    public function setUser(?UserDataBag $user): void
    {
        $this->user = $user;
    }

    /**
     * Gets the server OS context.
     */
    public function getOsContext(): ?OsContext
    {
        return $this->osContext;
    }

    /**
     * Sets the server OS context.
     *
     * @param OsContext|null $osContext The context object
     */
    public function setOsContext(?OsContext $osContext): void
    {
        $this->osContext = $osContext;
    }

    /**
     * Gets the runtime context data.
     */
    public function getRuntimeContext(): ?RuntimeContext
    {
        return $this->runtimeContext;
    }

    /**
     * Sets the runtime context data.
     *
     * @param RuntimeContext|null $runtimeContext The context object
     */
    public function setRuntimeContext(?RuntimeContext $runtimeContext): void
    {
        $this->runtimeContext = $runtimeContext;
    }

    /**
     * Gets an array of strings used to dictate the deduplication of this
     * event.
     *
     * @return string[]
     */
    public function getFingerprint(): array
    {
        return $this->fingerprint;
    }

    /**
     * Sets an array of strings used to dictate the deduplication of this
     * event.
     *
     * @param string[] $fingerprint The strings
     */
    public function setFingerprint(array $fingerprint): void
    {
        $this->fingerprint = $fingerprint;
    }

    /**
     * Gets the environment in which this event was generated.
     */
    public function getEnvironment(): ?string
    {
        return $this->environment;
    }

    /**
     * Sets the environment in which this event was generated.
     *
     * @param string|null $environment The name of the environment
     */
    public function setEnvironment(?string $environment): void
    {
        $this->environment = $environment;
    }

    /**
     * Gets the breadcrumbs.
     *
     * @return Breadcrumb[]
     */
    public function getBreadcrumbs(): array
    {
        return $this->breadcrumbs;
    }

    /**
     * Set new breadcrumbs to the event.
     *
     * @param Breadcrumb[] $breadcrumbs The breadcrumb array
     */
    public function setBreadcrumb(array $breadcrumbs): void
    {
        $this->breadcrumbs = $breadcrumbs;
    }

    /**
     * Gets the exception.
     *
     * @return ExceptionDataBag[]
     */
    public function getExceptions(): array
    {
        return $this->exceptions;
    }

    /**
     * Sets the exceptions.
     *
     * @param ExceptionDataBag[] $exceptions The exceptions
     */
    public function setExceptions(array $exceptions): void
    {
        foreach ($exceptions as $exception) {
            if (!$exception instanceof ExceptionDataBag) {
                throw new \UnexpectedValueException(sprintf('Expected an instance of the "%s" class. Got: "%s".', ExceptionDataBag::class, get_debug_type($exception)));
            }
        }

        $this->exceptions = $exceptions;
    }

    /**
     * Gets the stacktrace that generated this event.
     */
    public function getStacktrace(): ?Stacktrace
    {
        return $this->stacktrace;
    }

    /**
     * Sets the stacktrace that generated this event.
     *
     * @param Stacktrace|null $stacktrace The stacktrace instance
     */
    public function setStacktrace(?Stacktrace $stacktrace): void
    {
        $this->stacktrace = $stacktrace;
    }

    public function getType(): EventType
    {
        return $this->type;
    }

    /**
     * Sets the SDK metadata with the given name.
     *
     * @param string $name The name that uniquely identifies the SDK metadata
     * @param mixed  $data The data of the SDK metadata
     */
    public function setSdkMetadata(string $name, $data): void
    {
        $this->sdkMetadata[$name] = $data;
    }

    /**
     * Gets the SDK metadata.
     *
     * @return mixed
     *
     * @psalm-template T of string|null
     *
     * @psalm-param T $name
     *
     * @psalm-return (T is string ? mixed : array<string, mixed>|null)
     */
    public function getSdkMetadata(?string $name = null)
    {
        if (null !== $name) {
            return $this->sdkMetadata[$name] ?? null;
        }

        return $this->sdkMetadata;
    }

    /**
     * Gets a timestamp representing when the measuring of a transaction started.
     */
    public function getStartTimestamp(): ?float
    {
        return $this->startTimestamp;
    }

    /**
     * Sets a timestamp representing when the measuring of a transaction started.
     *
     * @param float|null $startTimestamp The start time of the measurement
     */
    public function setStartTimestamp(?float $startTimestamp): void
    {
        $this->startTimestamp = $startTimestamp;
    }

    /**
     * A list of timed application events that have a start and end time.
     *
     * @return Span[]
     */
    public function getSpans(): array
    {
        return $this->spans;
    }

    /**
     * Sets a list of timed application events that have a start and end time.
     *
     * @param Span[] $spans The list of spans
     */
    public function setSpans(array $spans): void
    {
        $this->spans = $spans;
    }

    public function setProfile(?Profile $profile): void
    {
        $this->profile = $profile;
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function getTraceId(): ?string
    {
        $traceId = $this->getContexts()['trace']['trace_id'];

        if (\is_string($traceId) && !empty($traceId)) {
            return $traceId;
        }

        return null;
    }
}
