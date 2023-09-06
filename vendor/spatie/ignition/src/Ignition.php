<?php

namespace Spatie\Ignition;

use ArrayObject;
use ErrorException;
use Spatie\FlareClient\Context\BaseContextProviderDetector;
use Spatie\FlareClient\Context\ContextProviderDetector;
use Spatie\FlareClient\Enums\MessageLevels;
use Spatie\FlareClient\Flare;
use Spatie\FlareClient\FlareMiddleware\AddDocumentationLinks;
use Spatie\FlareClient\FlareMiddleware\AddSolutions;
use Spatie\FlareClient\FlareMiddleware\FlareMiddleware;
use Spatie\FlareClient\Report;
use Spatie\Ignition\Config\IgnitionConfig;
use Spatie\Ignition\Contracts\HasSolutionsForThrowable;
use Spatie\Ignition\Contracts\SolutionProviderRepository as SolutionProviderRepositoryContract;
use Spatie\Ignition\ErrorPage\ErrorPageViewModel;
use Spatie\Ignition\ErrorPage\Renderer;
use Spatie\Ignition\Solutions\SolutionProviders\BadMethodCallSolutionProvider;
use Spatie\Ignition\Solutions\SolutionProviders\MergeConflictSolutionProvider;
use Spatie\Ignition\Solutions\SolutionProviders\SolutionProviderRepository;
use Spatie\Ignition\Solutions\SolutionProviders\UndefinedPropertySolutionProvider;
use Throwable;

class Ignition
{
    protected Flare $flare;

    protected bool $shouldDisplayException = true;

    protected string $flareApiKey = '';

    protected string $applicationPath = '';

    /** @var array<int, FlareMiddleware> */
    protected array $middleware = [];

    protected IgnitionConfig $ignitionConfig;

    protected ContextProviderDetector $contextProviderDetector;

    protected SolutionProviderRepositoryContract $solutionProviderRepository;

    protected ?bool $inProductionEnvironment = null;

    protected ?string $solutionTransformerClass = null;

    /** @var ArrayObject<int, callable(Throwable): mixed> */
    protected ArrayObject $documentationLinkResolvers;

    protected string $customHtmlHead = '';

    protected string $customHtmlBody = '';

    public static function make(): self
    {
        return new self();
    }

    public function __construct()
    {
        $this->flare = Flare::make();

        $this->ignitionConfig = IgnitionConfig::loadFromConfigFile();

        $this->solutionProviderRepository = new SolutionProviderRepository($this->getDefaultSolutionProviders());

        $this->documentationLinkResolvers = new ArrayObject();

        $this->contextProviderDetector = new BaseContextProviderDetector();

        $this->middleware[] = new AddSolutions($this->solutionProviderRepository);
        $this->middleware[] = new AddDocumentationLinks($this->documentationLinkResolvers);
    }

    public function setSolutionTransformerClass(string $solutionTransformerClass): self
    {
        $this->solutionTransformerClass = $solutionTransformerClass;

        return $this;
    }

    /** @param callable(Throwable): mixed $callable */
    public function resolveDocumentationLink(callable $callable): self
    {
        $this->documentationLinkResolvers[] = $callable;

        return $this;
    }

    public function setConfig(IgnitionConfig $ignitionConfig): self
    {
        $this->ignitionConfig = $ignitionConfig;

        return $this;
    }

    public function runningInProductionEnvironment(bool $boolean = true): self
    {
        $this->inProductionEnvironment = $boolean;

        return $this;
    }

    public function getFlare(): Flare
    {
        return $this->flare;
    }

    public function setFlare(Flare $flare): self
    {
        $this->flare = $flare;

        return $this;
    }

    public function setSolutionProviderRepository(SolutionProviderRepositoryContract $solutionProviderRepository): self
    {
        $this->solutionProviderRepository = $solutionProviderRepository;

        return $this;
    }

    public function shouldDisplayException(bool $shouldDisplayException): self
    {
        $this->shouldDisplayException = $shouldDisplayException;

        return $this;
    }

    public function applicationPath(string $applicationPath): self
    {
        $this->applicationPath = $applicationPath;

        return $this;
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
        $this->flare->glow($name, $messageLevel, $metaData);

        return $this;
    }

    /**
     * @param array<int, HasSolutionsForThrowable|class-string<HasSolutionsForThrowable>> $solutionProviders
     *
     * @return $this
     */
    public function addSolutionProviders(array $solutionProviders): self
    {
        $this->solutionProviderRepository->registerSolutionProviders($solutionProviders);

        return $this;
    }

    /** @deprecated Use `setTheme('dark')` instead */
    public function useDarkMode(): self
    {
        return $this->setTheme('dark');
    }

    /** @deprecated Use `setTheme($theme)` instead */
    public function theme(string $theme): self
    {
        return $this->setTheme($theme);
    }

    public function setTheme(string $theme): self
    {
        $this->ignitionConfig->setOption('theme', $theme);

        return $this;
    }

    public function setEditor(string $editor): self
    {
        $this->ignitionConfig->setOption('editor', $editor);

        return $this;
    }

    public function sendToFlare(?string $apiKey): self
    {
        $this->flareApiKey = $apiKey ?? '';

        return $this;
    }

    public function configureFlare(callable $callable): self
    {
        ($callable)($this->flare);

        return $this;
    }

    /**
     * @param FlareMiddleware|array<int, FlareMiddleware> $middleware
     *
     * @return $this
     */
    public function registerMiddleware(array|FlareMiddleware $middleware): self
    {
        if (! is_array($middleware)) {
            $middleware = [$middleware];
        }

        foreach ($middleware as $singleMiddleware) {
            $this->middleware = array_merge($this->middleware, $middleware);
        }

        return $this;
    }

    public function setContextProviderDetector(ContextProviderDetector $contextProviderDetector): self
    {
        $this->contextProviderDetector = $contextProviderDetector;

        return $this;
    }

    public function reset(): self
    {
        $this->flare->reset();

        return $this;
    }

    public function register(): self
    {
        error_reporting(-1);

        /** @phpstan-ignore-next-line  */
        set_error_handler([$this, 'renderError']);

        /** @phpstan-ignore-next-line  */
        set_exception_handler([$this, 'handleException']);

        return $this;
    }

    /**
     * @param int $level
     * @param string $message
     * @param string $file
     * @param int $line
     * @param array<int, mixed> $context
     *
     * @return void
     * @throws \ErrorException
     */
    public function renderError(
        int $level,
        string $message,
        string $file = '',
        int $line = 0,
        array $context = []
    ): void {
        throw new ErrorException($message, 0, $level, $file, $line);
    }

    /**
     * This is the main entry point for the framework agnostic Ignition package.
     * Displays the Ignition page and optionally sends a report to Flare.
     */
    public function handleException(Throwable $throwable): Report
    {
        $this->setUpFlare();

        $report = $this->createReport($throwable);

        if ($this->shouldDisplayException && $this->inProductionEnvironment !== true) {
            $this->renderException($throwable, $report);
        }

        if ($this->flare->apiTokenSet() && $this->inProductionEnvironment !== false) {
            $this->flare->report($throwable, report: $report);
        }

        return $report;
    }

    /**
     * This is the main entrypoint for laravel-ignition. It only renders the exception.
     * Sending the report to Flare is handled in the laravel-ignition log handler.
     */
    public function renderException(Throwable $throwable, ?Report $report = null): void
    {
        $this->setUpFlare();

        $report ??= $this->createReport($throwable);

        $viewModel = new ErrorPageViewModel(
            $throwable,
            $this->ignitionConfig,
            $report,
            $this->solutionProviderRepository->getSolutionsForThrowable($throwable),
            $this->solutionTransformerClass,
            $this->customHtmlHead,
            $this->customHtmlBody,
        );

        (new Renderer())->render(['viewModel' => $viewModel], self::viewPath('errorPage'));
    }

    public static function viewPath(string $viewName): string
    {
        return __DIR__ . "/../resources/views/{$viewName}.php";
    }

    /**
     * Add custom HTML which will be added to the head tag of the error page.
     */
    public function addCustomHtmlToHead(string $html): self
    {
        $this->customHtmlHead .= $html;

        return $this;
    }

    /**
     * Add custom HTML which will be added to the body tag of the error page.
     */
    public function addCustomHtmlToBody(string $html): self
    {
        $this->customHtmlBody .= $html;

        return $this;
    }

    protected function setUpFlare(): self
    {
        if (! $this->flare->apiTokenSet()) {
            $this->flare->setApiToken($this->flareApiKey ?? '');
        }

        $this->flare->setContextProviderDetector($this->contextProviderDetector);

        foreach ($this->middleware as $singleMiddleware) {
            $this->flare->registerMiddleware($singleMiddleware);
        }

        if ($this->applicationPath !== '') {
            $this->flare->applicationPath($this->applicationPath);
        }

        return $this;
    }

    /** @return array<class-string<HasSolutionsForThrowable>> */
    protected function getDefaultSolutionProviders(): array
    {
        return [
            BadMethodCallSolutionProvider::class,
            MergeConflictSolutionProvider::class,
            UndefinedPropertySolutionProvider::class,
        ];
    }

    protected function createReport(Throwable $throwable): Report
    {
        return $this->flare->createReport($throwable);
    }
}
