<?php

namespace Spatie\LaravelIgnition;

use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\ViewException;
use Laravel\Octane\Events\RequestReceived;
use Laravel\Octane\Events\RequestTerminated;
use Laravel\Octane\Events\TaskReceived;
use Laravel\Octane\Events\TickReceived;
use Monolog\Level;
use Monolog\Logger;
use Spatie\FlareClient\Flare;
use Spatie\FlareClient\FlareMiddleware\AddSolutions;
use Spatie\Ignition\Config\FileConfigManager;
use Spatie\Ignition\Config\IgnitionConfig;
use Spatie\Ignition\Contracts\ConfigManager;
use Spatie\Ignition\Contracts\SolutionProviderRepository as SolutionProviderRepositoryContract;
use Spatie\Ignition\Ignition;
use Spatie\LaravelIgnition\Commands\SolutionMakeCommand;
use Spatie\LaravelIgnition\Commands\SolutionProviderMakeCommand;
use Spatie\LaravelIgnition\Commands\TestCommand;
use Spatie\LaravelIgnition\ContextProviders\LaravelContextProviderDetector;
use Spatie\LaravelIgnition\Exceptions\InvalidConfig;
use Spatie\LaravelIgnition\FlareMiddleware\AddJobs;
use Spatie\LaravelIgnition\FlareMiddleware\AddLogs;
use Spatie\LaravelIgnition\FlareMiddleware\AddQueries;
use Spatie\LaravelIgnition\Recorders\DumpRecorder\DumpRecorder;
use Spatie\LaravelIgnition\Recorders\JobRecorder\JobRecorder;
use Spatie\LaravelIgnition\Recorders\LogRecorder\LogRecorder;
use Spatie\LaravelIgnition\Recorders\QueryRecorder\QueryRecorder;
use Spatie\LaravelIgnition\Renderers\IgnitionExceptionRenderer;
use Spatie\LaravelIgnition\Solutions\SolutionProviders\SolutionProviderRepository;
use Spatie\LaravelIgnition\Support\FlareLogHandler;
use Spatie\LaravelIgnition\Support\SentReports;
use Spatie\LaravelIgnition\Views\ViewExceptionMapper;

class IgnitionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerConfig();
        $this->registerFlare();
        $this->registerIgnition();
        $this->registerRenderer();
        $this->registerRecorders();
        $this->registerLogHandler();
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->registerCommands();
            $this->publishConfigs();
        }

        $this->registerRoutes();
        $this->configureTinker();
        $this->configureOctane();
        $this->registerViewExceptionMapper();
        $this->startRecorders();
        $this->configureQueue();
    }

    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/flare.php', 'flare');
        $this->mergeConfigFrom(__DIR__ . '/../config/ignition.php', 'ignition');
    }

    protected function registerCommands(): void
    {
        if ($this->app['config']->get('flare.key')) {
            $this->commands([
                TestCommand::class,
            ]);
        }

        if ($this->app['config']->get('ignition.register_commands')) {
            $this->commands([
                SolutionMakeCommand::class,
                SolutionProviderMakeCommand::class,
            ]);
        }
    }

    protected function publishConfigs(): void
    {
        $this->publishes([
            __DIR__ . '/../config/ignition.php' => config_path('ignition.php'),
        ], 'ignition-config');

        $this->publishes([
            __DIR__ . '/../config/flare.php' => config_path('flare.php'),
        ], 'flare-config');
    }

    protected function registerRenderer(): void
    {
        $this->app->bind(
            'Illuminate\Contracts\Foundation\ExceptionRenderer',
            fn (Application $app) => $app->make(IgnitionExceptionRenderer::class)
        );
    }

    protected function registerFlare(): void
    {
        $this->app->singleton(Flare::class, function () {
            return Flare::make()
                ->setApiToken(config('flare.key') ?? '')
                ->setBaseUrl(config('flare.base_url', 'https://flareapp.io/api'))
                ->applicationPath(base_path())
                ->setStage(app()->environment())
                ->setContextProviderDetector(new LaravelContextProviderDetector())
                ->registerMiddleware($this->getFlareMiddleware())
                ->registerMiddleware(new AddSolutions(new SolutionProviderRepository($this->getSolutionProviders())))
                ->argumentReducers(config('ignition.argument_reducers', []))
                ->withStackFrameArguments(config('ignition.with_stack_frame_arguments', true));
        });

        $this->app->singleton(SentReports::class);
    }

    protected function registerIgnition(): void
    {
        $this->app->singleton(
            ConfigManager::class,
            fn () => new FileConfigManager(config('ignition.settings_file_path', ''))
        );

        $ignitionConfig = (new IgnitionConfig())
            ->merge(config('ignition', []))
            ->loadConfigFile();

        $solutionProviders = $this->getSolutionProviders();
        $solutionProviderRepository = new SolutionProviderRepository($solutionProviders);

        $this->app->singleton(IgnitionConfig::class, fn () => $ignitionConfig);

        $this->app->singleton(SolutionProviderRepositoryContract::class, fn () => $solutionProviderRepository);

        $this->app->singleton(
            Ignition::class,
            fn () => (new Ignition())
                ->applicationPath(base_path())
        );
    }

    protected function registerRecorders(): void
    {
        $this->app->singleton(DumpRecorder::class);

        $this->app->singleton(LogRecorder::class, function (Application $app): LogRecorder {
            return new LogRecorder(
                $app,
                config()->get('flare.flare_middleware.' . AddLogs::class . '.maximum_number_of_collected_logs')
            );
        });

        $this->app->singleton(
            QueryRecorder::class,
            function (Application $app): QueryRecorder {
                return new QueryRecorder(
                    $app,
                    config('flare.flare_middleware.' . AddQueries::class . '.report_query_bindings', true),
                    config('flare.flare_middleware.' . AddQueries::class . '.maximum_number_of_collected_queries', 200)
                );
            }
        );

        $this->app->singleton(JobRecorder::class, function (Application $app): JobRecorder {
            return new JobRecorder(
                $app,
                config('flare.flare_middleware.' . AddJobs::class . '.max_chained_job_reporting_depth', 5)
            );
        });
    }

    public function configureTinker(): void
    {
        if (! $this->app->runningInConsole()) {
            if (isset($_SERVER['argv']) && ['artisan', 'tinker'] === $_SERVER['argv']) {
                app(Flare::class)->sendReportsImmediately();
            }
        }
    }

    protected function configureOctane(): void
    {
        if (isset($_SERVER['LARAVEL_OCTANE'])) {
            $this->setupOctane();
        }
    }

    protected function registerViewExceptionMapper(): void
    {
        $handler = $this->app->make(ExceptionHandler::class);

        if (! method_exists($handler, 'map')) {
            return;
        }

        $handler->map(function (ViewException $viewException) {
            return $this->app->make(ViewExceptionMapper::class)->map($viewException);
        });
    }

    protected function registerRoutes(): void
    {
        $this->loadRoutesFrom(realpath(__DIR__ . '/ignition-routes.php'));
    }

    protected function registerLogHandler(): void
    {
        $this->app->singleton('flare.logger', function ($app) {
            $handler = new FlareLogHandler(
                $app->make(Flare::class),
                $app->make(SentReports::class),
            );

            $logLevelString = config('logging.channels.flare.level', 'error');

            $logLevel = $this->getLogLevel($logLevelString);

            $handler->setMinimumReportLogLevel($logLevel);

            return tap(
                new Logger('Flare'),
                fn (Logger $logger) => $logger->pushHandler($handler)
            );
        });

        Log::extend('flare', fn ($app) => $app['flare.logger']);
    }

    protected function startRecorders(): void
    {
        foreach ($this->app->config['ignition.recorders'] ?? [] as $recorder) {
            $this->app->make($recorder)->start();
        }
    }

    protected function configureQueue(): void
    {
        if (! $this->app->bound('queue')) {
            return;
        }

        $queue = $this->app->get('queue');

        // Reset before executing a queue job to make sure the job's log/query/dump recorders are empty.
        // When using a sync queue this also reports the queued reports from previous exceptions.
        $queue->before(function () {
            $this->resetFlareAndLaravelIgnition();
            app(Flare::class)->sendReportsImmediately();
        });

        // Send queued reports (and reset) after executing a queue job.
        $queue->after(function () {
            $this->resetFlareAndLaravelIgnition();
        });

        // Note: the $queue->looping() event can't be used because it's not triggered on Vapor
    }

    protected function getLogLevel(string $logLevelString): int
    {
        try {
            $logLevel = Level::fromName($logLevelString);
        } catch (Exception $exception) {
            $logLevel = null;
        }

        if (! $logLevel) {
            throw InvalidConfig::invalidLogLevel($logLevelString);
        }

        return $logLevel->value;
    }

    protected function getFlareMiddleware(): array
    {
        return collect(config('flare.flare_middleware'))
            ->map(function ($value, $key) {
                if (is_string($key)) {
                    $middlewareClass = $key;
                    $parameters = $value ?? [];
                } else {
                    $middlewareClass = $value;
                    $parameters = [];
                }

                return new $middlewareClass(...array_values($parameters));
            })
            ->values()
            ->toArray();
    }

    protected function getSolutionProviders(): array
    {
        return collect(config('ignition.solution_providers'))
            ->reject(
                fn (string $class) => in_array($class, config('ignition.ignored_solution_providers'))
            )
            ->toArray();
    }

    protected function setupOctane(): void
    {
        $this->app['events']->listen(RequestReceived::class, function () {
            $this->resetFlareAndLaravelIgnition();
        });

        $this->app['events']->listen(TaskReceived::class, function () {
            $this->resetFlareAndLaravelIgnition();
        });

        $this->app['events']->listen(TickReceived::class, function () {
            $this->resetFlareAndLaravelIgnition();
        });

        $this->app['events']->listen(RequestTerminated::class, function () {
            $this->resetFlareAndLaravelIgnition();
        });
    }

    protected function resetFlareAndLaravelIgnition(): void
    {
        $this->app->get(SentReports::class)->clear();
        $this->app->get(Ignition::class)->reset();

        if (config('flare.flare_middleware.' . AddLogs::class)) {
            $this->app->make(LogRecorder::class)->reset();
        }

        if (config('flare.flare_middleware.' . AddQueries::class)) {
            $this->app->make(QueryRecorder::class)->reset();
        }

        if (config('flare.flare_middleware.' . AddJobs::class)) {
            $this->app->make(JobRecorder::class)->reset();
        }

        $this->app->make(DumpRecorder::class)->reset();
    }
}
