<?php

namespace Spatie\FlareClient;

use Error;
use ErrorException;
use Exception;
use Illuminate\Contracts\Container\Container;
use Illuminate\Pipeline\Pipeline;
use Spatie\Backtrace\Arguments\ArgumentReducers;
use Spatie\Backtrace\Arguments\Reducers\ArgumentReducer;
use Spatie\FlareClient\Concerns\HasContext;
use Spatie\FlareClient\Context\BaseContextProviderDetector;
use Spatie\FlareClient\Context\ContextProviderDetector;
use Spatie\FlareClient\Enums\MessageLevels;
use Spatie\FlareClient\FlareMiddleware\AddEnvironmentInformation;
use Spatie\FlareClient\FlareMiddleware\AddGlows;
use Spatie\FlareClient\FlareMiddleware\CensorRequestBodyFields;
use Spatie\FlareClient\FlareMiddleware\FlareMiddleware;
use Spatie\FlareClient\FlareMiddleware\RemoveRequestIp;
use Spatie\FlareClient\Glows\Glow;
use Spatie\FlareClient\Glows\GlowRecorder;
use Spatie\FlareClient\Http\Client;
use Throwable;

class Flare
{
    use HasContext;

    protected Client $client;

    protected Api $api;

    /** @var array<int, FlareMiddleware|class-string<FlareMiddleware>> */
    protected array $middleware = [];

    protected GlowRecorder $recorder;

    protected ?string $applicationPath = null;

    protected ContextProviderDetector $contextDetector;

    protected $previousExceptionHandler = null;

    /** @var null|callable */
    protected $previousErrorHandler = null;

    /** @var null|callable */
    protected $determineVersionCallable = null;

    protected ?int $reportErrorLevels = null;

    /** @var null|callable */
    protected $filterExceptionsCallable = null;

    /** @var null|callable */
    protected $filterReportsCallable = null;

    protected ?string $stage = null;

    protected ?string $requestId = null;

    protected ?Container $container = null;

    /** @var array<class-string<ArgumentReducer>|ArgumentReducer>|ArgumentReducers|null */
    protected null|array|ArgumentReducers $argumentReducers = null;

    protected bool $withStackFrameArguments = true;

    public static function make(
        string $apiKey = null,
        ContextProviderDetector $contextDetector = null
    ): self {
        $client = new Client($apiKey);

        return new self($client, $contextDetector);
    }

    public function setApiToken(string $apiToken): self
    {
        $this->client->setApiToken($apiToken);

        return $this;
    }

    public function apiTokenSet(): bool
    {
        return $this->client->apiTokenSet();
    }

    public function setBaseUrl(string $baseUrl): self
    {
        $this->client->setBaseUrl($baseUrl);

        return $this;
    }

    public function setStage(?string $stage): self
    {
        $this->stage = $stage;

        return $this;
    }

    public function sendReportsImmediately(): self
    {
        $this->api->sendReportsImmediately();

        return $this;
    }

    public function determineVersionUsing(callable $determineVersionCallable): self
    {
        $this->determineVersionCallable = $determineVersionCallable;

        return $this;
    }

    public function reportErrorLevels(int $reportErrorLevels): self
    {
        $this->reportErrorLevels = $reportErrorLevels;

        return $this;
    }

    public function filterExceptionsUsing(callable $filterExceptionsCallable): self
    {
        $this->filterExceptionsCallable = $filterExceptionsCallable;

        return $this;
    }

    public function filterReportsUsing(callable $filterReportsCallable): self
    {
        $this->filterReportsCallable = $filterReportsCallable;

        return $this;
    }

    /** @param array<class-string<ArgumentReducer>|ArgumentReducer>|ArgumentReducers|null $argumentReducers */
    public function argumentReducers(null|array|ArgumentReducers $argumentReducers): self
    {
        $this->argumentReducers = $argumentReducers;

        return $this;
    }

    public function withStackFrameArguments(bool $withStackFrameArguments = true): self
    {
        $this->withStackFrameArguments = $withStackFrameArguments;

        return $this;
    }

    public function version(): ?string
    {
        if (! $this->determineVersionCallable) {
            return null;
        }

        return ($this->determineVersionCallable)();
    }

    /**
     * @param \Spatie\FlareClient\Http\Client $client
     * @param \Spatie\FlareClient\Context\ContextProviderDetector|null $contextDetector
     * @param array<int, FlareMiddleware> $middleware
     */
    public function __construct(
        Client $client,
        ContextProviderDetector $contextDetector = null,
        array $middleware = [],
    ) {
        $this->client = $client;
        $this->recorder = new GlowRecorder();
        $this->contextDetector = $contextDetector ?? new BaseContextProviderDetector();
        $this->middleware = $middleware;
        $this->api = new Api($this->client);

        $this->registerDefaultMiddleware();
    }

    /** @return array<int, FlareMiddleware|class-string<FlareMiddleware>> */
    public function getMiddleware(): array
    {
        return $this->middleware;
    }

    public function setContextProviderDetector(ContextProviderDetector $contextDetector): self
    {
        $this->contextDetector = $contextDetector;

        return $this;
    }

    public function setContainer(Container $container): self
    {
        $this->container = $container;

        return $this;
    }

    public function registerFlareHandlers(): self
    {
        $this->registerExceptionHandler();

        $this->registerErrorHandler();

        return $this;
    }

    public function registerExceptionHandler(): self
    {
        /** @phpstan-ignore-next-line */
        $this->previousExceptionHandler = set_exception_handler([$this, 'handleException']);

        return $this;
    }

    public function registerErrorHandler(): self
    {
        $this->previousErrorHandler = set_error_handler([$this, 'handleError']);

        return $this;
    }

    protected function registerDefaultMiddleware(): self
    {
        return $this->registerMiddleware([
            new AddGlows($this->recorder),
            new AddEnvironmentInformation(),
        ]);
    }

    /**
     * @param FlareMiddleware|array<FlareMiddleware>|class-string<FlareMiddleware>|callable $middleware
     *
     * @return $this
     */
    public function registerMiddleware($middleware): self
    {
        if (! is_array($middleware)) {
            $middleware = [$middleware];
        }

        $this->middleware = array_merge($this->middleware, $middleware);

        return $this;
    }

    /**
     * @return array<int,FlareMiddleware|class-string<FlareMiddleware>>
     */
    public function getMiddlewares(): array
    {
        return $this->middleware;
    }

    /**
     * @param string $name
     * @param string $messageLevel
     * @param array<int, mixed> $metaData
     *
     * @return $this
     */
    public function glow(
        string $name,
        string $messageLevel = MessageLevels::INFO,
        array $metaData = []
    ): self {
        $this->recorder->record(new Glow($name, $messageLevel, $metaData));

        return $this;
    }

    public function handleException(Throwable $throwable): void
    {
        $this->report($throwable);

        if ($this->previousExceptionHandler && is_callable($this->previousExceptionHandler)) {
            call_user_func($this->previousExceptionHandler, $throwable);
        }
    }

    /**
     * @return mixed
     */
    public function handleError(mixed $code, string $message, string $file = '', int $line = 0)
    {
        $exception = new ErrorException($message, 0, $code, $file, $line);

        $this->report($exception);

        if ($this->previousErrorHandler) {
            return call_user_func(
                $this->previousErrorHandler,
                $message,
                $code,
                $file,
                $line
            );
        }
    }

    public function applicationPath(string $applicationPath): self
    {
        $this->applicationPath = $applicationPath;

        return $this;
    }

    public function report(Throwable $throwable, callable $callback = null, Report $report = null): ?Report
    {
        if (! $this->shouldSendReport($throwable)) {
            return null;
        }

        $report ??= $this->createReport($throwable);

        if (! is_null($callback)) {
            call_user_func($callback, $report);
        }

        $this->recorder->reset();

        $this->sendReportToApi($report);

        return $report;
    }

    protected function shouldSendReport(Throwable $throwable): bool
    {
        if (isset($this->reportErrorLevels) && $throwable instanceof Error) {
            return (bool) ($this->reportErrorLevels & $throwable->getCode());
        }

        if (isset($this->reportErrorLevels) && $throwable instanceof ErrorException) {
            return (bool) ($this->reportErrorLevels & $throwable->getSeverity());
        }

        if ($this->filterExceptionsCallable && $throwable instanceof Exception) {
            return (bool) (call_user_func($this->filterExceptionsCallable, $throwable));
        }

        return true;
    }

    public function reportMessage(string $message, string $logLevel, callable $callback = null): void
    {
        $report = $this->createReportFromMessage($message, $logLevel);

        if (! is_null($callback)) {
            call_user_func($callback, $report);
        }

        $this->sendReportToApi($report);
    }

    public function sendTestReport(Throwable $throwable): void
    {
        $this->api->sendTestReport($this->createReport($throwable));
    }

    protected function sendReportToApi(Report $report): void
    {
        if ($this->filterReportsCallable) {
            if (! call_user_func($this->filterReportsCallable, $report)) {
                return;
            }
        }

        try {
            $this->api->report($report);
        } catch (Exception $exception) {
        }
    }

    public function reset(): void
    {
        $this->api->sendQueuedReports();

        $this->userProvidedContext = [];

        $this->recorder->reset();
    }

    protected function applyAdditionalParameters(Report $report): void
    {
        $report
            ->stage($this->stage)
            ->messageLevel($this->messageLevel)
            ->setApplicationPath($this->applicationPath)
            ->userProvidedContext($this->userProvidedContext);
    }

    public function anonymizeIp(): self
    {
        $this->registerMiddleware(new RemoveRequestIp());

        return $this;
    }

    /**
     * @param array<int, string> $fieldNames
     *
     * @return $this
     */
    public function censorRequestBodyFields(array $fieldNames): self
    {
        $this->registerMiddleware(new CensorRequestBodyFields($fieldNames));

        return $this;
    }

    public function createReport(Throwable $throwable): Report
    {
        $report = Report::createForThrowable(
            $throwable,
            $this->contextDetector->detectCurrentContext(),
            $this->applicationPath,
            $this->version(),
            $this->argumentReducers,
            $this->withStackFrameArguments
        );

        return $this->applyMiddlewareToReport($report);
    }

    public function createReportFromMessage(string $message, string $logLevel): Report
    {
        $report = Report::createForMessage(
            $message,
            $logLevel,
            $this->contextDetector->detectCurrentContext(),
            $this->applicationPath,
            $this->argumentReducers,
            $this->withStackFrameArguments
        );

        return $this->applyMiddlewareToReport($report);
    }

    protected function applyMiddlewareToReport(Report $report): Report
    {
        $this->applyAdditionalParameters($report);
        $middleware = array_map(function ($singleMiddleware) {
            return is_string($singleMiddleware)
                ? new $singleMiddleware
                : $singleMiddleware;
        }, $this->middleware);

        $report = (new Pipeline())
            ->send($report)
            ->through($middleware)
            ->then(fn ($report) => $report);

        return $report;
    }
}
