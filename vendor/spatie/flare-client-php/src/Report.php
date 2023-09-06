<?php

namespace Spatie\FlareClient;

use ErrorException;
use Spatie\Backtrace\Arguments\ArgumentReducers;
use Spatie\Backtrace\Arguments\Reducers\ArgumentReducer;
use Spatie\Backtrace\Backtrace;
use Spatie\Backtrace\Frame as SpatieFrame;
use Spatie\FlareClient\Concerns\HasContext;
use Spatie\FlareClient\Concerns\UsesTime;
use Spatie\FlareClient\Context\ContextProvider;
use Spatie\FlareClient\Contracts\ProvidesFlareContext;
use Spatie\FlareClient\Glows\Glow;
use Spatie\FlareClient\Solutions\ReportSolution;
use Spatie\Ignition\Contracts\Solution;
use Spatie\LaravelIgnition\Exceptions\ViewException;
use Throwable;

class Report
{
    use UsesTime;
    use HasContext;

    protected Backtrace $stacktrace;

    protected string $exceptionClass = '';

    protected string $message = '';

    /** @var array<int, array{time: int, name: string, message_level: string, meta_data: array, microtime: float}> */
    protected array $glows = [];

    /** @var array<int, array<int|string, mixed>> */
    protected array $solutions = [];

    /** @var array<int, string> */
    public array $documentationLinks = [];

    protected ContextProvider $context;

    protected ?string $applicationPath = null;

    protected ?string $applicationVersion = null;

    /** @var array<int|string, mixed> */
    protected array $userProvidedContext = [];

    /** @var array<int|string, mixed> */
    protected array $exceptionContext = [];

    protected ?Throwable $throwable = null;

    protected string $notifierName = 'Flare Client';

    protected ?string $languageVersion = null;

    protected ?string $frameworkVersion = null;

    protected ?int $openFrameIndex = null;

    protected string $trackingUuid;

    protected ?View $view;

    public static ?string $fakeTrackingUuid = null;

    /** @param array<class-string<ArgumentReducer>|ArgumentReducer>|ArgumentReducers|null $argumentReducers */
    public static function createForThrowable(
        Throwable $throwable,
        ContextProvider $context,
        ?string $applicationPath = null,
        ?string $version = null,
        null|array|ArgumentReducers $argumentReducers = null,
        bool $withStackTraceArguments = true,
    ): self {
        $stacktrace = Backtrace::createForThrowable($throwable)
            ->withArguments($withStackTraceArguments)
            ->reduceArguments($argumentReducers)
            ->applicationPath($applicationPath ?? '');

        return (new self())
            ->setApplicationPath($applicationPath)
            ->throwable($throwable)
            ->useContext($context)
            ->exceptionClass(self::getClassForThrowable($throwable))
            ->message($throwable->getMessage())
            ->stackTrace($stacktrace)
            ->exceptionContext($throwable)
            ->setApplicationVersion($version);
    }

    protected static function getClassForThrowable(Throwable $throwable): string
    {
        /** @phpstan-ignore-next-line */
        if ($throwable::class === ViewException::class) {
            /** @phpstan-ignore-next-line */
            if ($previous = $throwable->getPrevious()) {
                return get_class($previous);
            }
        }

        return get_class($throwable);
    }

    /** @param array<class-string<ArgumentReducer>|ArgumentReducer>|ArgumentReducers|null $argumentReducers */
    public static function createForMessage(
        string $message,
        string $logLevel,
        ContextProvider $context,
        ?string $applicationPath = null,
        null|array|ArgumentReducers $argumentReducers = null,
        bool $withStackTraceArguments = true,
    ): self {
        $stacktrace = Backtrace::create()
            ->withArguments($withStackTraceArguments)
            ->reduceArguments($argumentReducers)
            ->applicationPath($applicationPath ?? '');

        return (new self())
            ->setApplicationPath($applicationPath)
            ->message($message)
            ->useContext($context)
            ->exceptionClass($logLevel)
            ->stacktrace($stacktrace)
            ->openFrameIndex($stacktrace->firstApplicationFrameIndex());
    }

    public function __construct()
    {
        $this->trackingUuid = self::$fakeTrackingUuid ?? $this->generateUuid();
    }

    public function trackingUuid(): string
    {
        return $this->trackingUuid;
    }

    public function exceptionClass(string $exceptionClass): self
    {
        $this->exceptionClass = $exceptionClass;

        return $this;
    }

    public function getExceptionClass(): string
    {
        return $this->exceptionClass;
    }

    public function throwable(Throwable $throwable): self
    {
        $this->throwable = $throwable;

        return $this;
    }

    public function getThrowable(): ?Throwable
    {
        return $this->throwable;
    }

    public function message(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function stacktrace(Backtrace $stacktrace): self
    {
        $this->stacktrace = $stacktrace;

        return $this;
    }

    public function getStacktrace(): Backtrace
    {
        return $this->stacktrace;
    }

    public function notifierName(string $notifierName): self
    {
        $this->notifierName = $notifierName;

        return $this;
    }

    public function languageVersion(string $languageVersion): self
    {
        $this->languageVersion = $languageVersion;

        return $this;
    }

    public function frameworkVersion(string $frameworkVersion): self
    {
        $this->frameworkVersion = $frameworkVersion;

        return $this;
    }

    public function useContext(ContextProvider $request): self
    {
        $this->context = $request;

        return $this;
    }

    public function openFrameIndex(?int $index): self
    {
        $this->openFrameIndex = $index;

        return $this;
    }

    public function setApplicationPath(?string $applicationPath): self
    {
        $this->applicationPath = $applicationPath;

        return $this;
    }

    public function getApplicationPath(): ?string
    {
        return $this->applicationPath;
    }

    public function setApplicationVersion(?string $applicationVersion): self
    {
        $this->applicationVersion = $applicationVersion;

        return $this;
    }

    public function getApplicationVersion(): ?string
    {
        return $this->applicationVersion;
    }

    public function view(?View $view): self
    {
        $this->view = $view;

        return $this;
    }

    public function addGlow(Glow $glow): self
    {
        $this->glows[] = $glow->toArray();

        return $this;
    }

    public function addSolution(Solution $solution): self
    {
        $this->solutions[] = ReportSolution::fromSolution($solution)->toArray();

        return $this;
    }

    /**
     * @param array<int, string> $documentationLinks
     *
     * @return $this
     */
    public function addDocumentationLinks(array $documentationLinks): self
    {
        $this->documentationLinks = $documentationLinks;

        return $this;
    }

    /**
     * @param array<int|string, mixed> $userProvidedContext
     *
     * @return $this
     */
    public function userProvidedContext(array $userProvidedContext): self
    {
        $this->userProvidedContext = $userProvidedContext;

        return $this;
    }

    /**
     * @return array<int|string, mixed>
     */
    public function allContext(): array
    {
        $context = $this->context->toArray();

        $context = array_merge_recursive_distinct($context, $this->exceptionContext);

        return array_merge_recursive_distinct($context, $this->userProvidedContext);
    }

    protected function exceptionContext(Throwable $throwable): self
    {
        if ($throwable instanceof ProvidesFlareContext) {
            $this->exceptionContext = $throwable->context();
        }

        return $this;
    }

    /**
     * @return array<int|string, mixed>
     */
    protected function stracktraceAsArray(): array
    {
        return array_map(
            fn (SpatieFrame $frame) => Frame::fromSpatieFrame($frame)->toArray(),
            $this->cleanupStackTraceForError($this->stacktrace->frames()),
        );
    }

    /**
     * @param array<SpatieFrame> $frames
     *
     * @return array<SpatieFrame>
     */
    protected function cleanupStackTraceForError(array $frames): array
    {
        if ($this->throwable === null || get_class($this->throwable) !== ErrorException::class) {
            return $frames;
        }

        $firstErrorFrameIndex = null;

        $restructuredFrames = array_values(array_slice($frames, 1)); // remove the first frame where error was created

        foreach ($restructuredFrames as $index => $frame) {
            if ($frame->file === $this->throwable->getFile()) {
                $firstErrorFrameIndex = $index;

                break;
            }
        }

        if ($firstErrorFrameIndex === null) {
            return $frames;
        }

        $restructuredFrames[$firstErrorFrameIndex]->arguments = null; // Remove error arguments

        return array_values(array_slice($restructuredFrames, $firstErrorFrameIndex));
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'notifier' => $this->notifierName ?? 'Flare Client',
            'language' => 'PHP',
            'framework_version' => $this->frameworkVersion,
            'language_version' => $this->languageVersion ?? phpversion(),
            'exception_class' => $this->exceptionClass,
            'seen_at' => $this->getCurrentTime(),
            'message' => $this->message,
            'glows' => $this->glows,
            'solutions' => $this->solutions,
            'documentation_links' => $this->documentationLinks,
            'stacktrace' => $this->stracktraceAsArray(),
            'context' => $this->allContext(),
            'stage' => $this->stage,
            'message_level' => $this->messageLevel,
            'open_frame_index' => $this->openFrameIndex,
            'application_path' => $this->applicationPath,
            'application_version' => $this->applicationVersion,
            'tracking_uuid' => $this->trackingUuid,
        ];
    }

    /*
     * Found on https://stackoverflow.com/questions/2040240/php-function-to-generate-v4-uuid/15875555#15875555
     */
    protected function generateUuid(): string
    {
        // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
        $data = random_bytes(16);

        // Set version to 0100
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        // Set bits 6-7 to 10
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        // Output the 36 character UUID.
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
