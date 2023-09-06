<?php

namespace Sentry\Laravel;

use Exception;
use Illuminate\Auth\Events as AuthEvents;
use Illuminate\Console\Events as ConsoleEvents;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Events as DatabaseEvents;
use Illuminate\Http\Client\Events as HttpClientEvents;
use Illuminate\Http\Request;
use Illuminate\Log\Events as LogEvents;
use Illuminate\Routing\Events as RoutingEvents;
use Laravel\Octane\Events as Octane;
use Laravel\Sanctum\Events as Sanctum;
use RuntimeException;
use Sentry\Breadcrumb;
use Sentry\Laravel\Util\WorksWithUris;
use Sentry\SentrySdk;
use Sentry\State\Scope;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;

class EventHandler
{
    use WorksWithUris;

    /**
     * Map event handlers to events.
     *
     * @var array
     */
    protected static $eventHandlerMap = [
        LogEvents\MessageLogged::class => 'messageLogged',
        RoutingEvents\RouteMatched::class => 'routeMatched',
        DatabaseEvents\QueryExecuted::class => 'queryExecuted',
        ConsoleEvents\CommandStarting::class => 'commandStarting',
        ConsoleEvents\CommandFinished::class => 'commandFinished',
        HttpClientEvents\ResponseReceived::class => 'httpClientResponseReceived',
        HttpClientEvents\ConnectionFailed::class => 'httpClientConnectionFailed',
    ];

    /**
     * Map authentication event handlers to events.
     *
     * @var array
     */
    protected static $authEventHandlerMap = [
        AuthEvents\Authenticated::class => 'authenticated',
        Sanctum\TokenAuthenticated::class => 'sanctumTokenAuthenticated', // Since Sanctum 2.13
    ];

    /**
     * Map Octane event handlers to events.
     *
     * @var array
     */
    protected static $octaneEventHandlerMap = [
        Octane\RequestReceived::class => 'octaneRequestReceived',
        Octane\RequestTerminated::class => 'octaneRequestTerminated',

        Octane\TaskReceived::class => 'octaneTaskReceived',
        Octane\TaskTerminated::class => 'octaneTaskTerminated',

        Octane\TickReceived::class => 'octaneTickReceived',
        Octane\TickTerminated::class => 'octaneTickTerminated',

        Octane\WorkerErrorOccurred::class => 'octaneWorkerErrorOccurred',
        Octane\WorkerStopping::class => 'octaneWorkerStopping',
    ];

    /**
     * The Laravel container.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    private $container;

    /**
     * Indicates if we should add SQL queries to the breadcrumbs.
     *
     * @var bool
     */
    private $recordSqlQueries;

    /**
     * Indicates if we should add query bindings to the breadcrumbs.
     *
     * @var bool
     */
    private $recordSqlBindings;

    /**
     * Indicates if we should add Laravel logs to the breadcrumbs.
     *
     * @var bool
     */
    private $recordLaravelLogs;

    /**
     * Indicates if we should add command info to the breadcrumbs.
     *
     * @var bool
     */
    private $recordCommandInfo;

    /**
     * Indicates if we should add tick info to the breadcrumbs.
     *
     * @var bool
     */
    private $recordOctaneTickInfo;

    /**
     * Indicates if we should add task info to the breadcrumbs.
     *
     * @var bool
     */
    private $recordOctaneTaskInfo;

    /**
     * Indicates if we should add HTTP client requests info to the breadcrumbs.
     *
     * @var bool
     */
    private $recordHttpClientRequests;

    /**
     * Indicates if we pushed a scope for Octane.
     *
     * @var bool
     */
    private $pushedOctaneScope = false;

    /**
     * EventHandler constructor.
     *
     * @param \Illuminate\Contracts\Container\Container $container
     * @param array                                     $config
     */
    public function __construct(Container $container, array $config)
    {
        $this->container = $container;

        $this->recordSqlQueries = ($config['breadcrumbs.sql_queries'] ?? $config['breadcrumbs']['sql_queries'] ?? true) === true;
        $this->recordSqlBindings = ($config['breadcrumbs.sql_bindings'] ?? $config['breadcrumbs']['sql_bindings'] ?? false) === true;
        $this->recordLaravelLogs = ($config['breadcrumbs.logs'] ?? $config['breadcrumbs']['logs'] ?? true) === true;
        $this->recordCommandInfo = ($config['breadcrumbs.command_info'] ?? $config['breadcrumbs']['command_info'] ?? true) === true;
        $this->recordOctaneTickInfo = ($config['breadcrumbs.octane_tick_info'] ?? $config['breadcrumbs']['octane_tick_info'] ?? true) === true;
        $this->recordOctaneTaskInfo = ($config['breadcrumbs.octane_task_info'] ?? $config['breadcrumbs']['octane_task_info'] ?? true) === true;
        $this->recordHttpClientRequests = ($config['breadcrumbs.http_client_requests'] ?? $config['breadcrumbs']['http_client_requests'] ?? true) === true;
    }

    /**
     * Attach all event handlers.
     */
    public function subscribe(Dispatcher $dispatcher): void
    {
        foreach (static::$eventHandlerMap as $eventName => $handler) {
            $dispatcher->listen($eventName, [$this, $handler]);
        }
    }

    /**
     * Attach all authentication event handlers.
     */
    public function subscribeAuthEvents(Dispatcher $dispatcher): void
    {
        foreach (static::$authEventHandlerMap as $eventName => $handler) {
            $dispatcher->listen($eventName, [$this, $handler]);
        }
    }

    /**
     * Attach all Octane event handlers.
     */
    public function subscribeOctaneEvents(Dispatcher $dispatcher): void
    {
        foreach (static::$octaneEventHandlerMap as $eventName => $handler) {
            $dispatcher->listen($eventName, [$this, $handler]);
        }
    }

    /**
     * Pass through the event and capture any errors.
     *
     * @param string $method
     * @param array  $arguments
     */
    public function __call(string $method, array $arguments)
    {
        $handlerMethod = "{$method}Handler";

        if (!method_exists($this, $handlerMethod)) {
            throw new RuntimeException("Missing event handler: {$handlerMethod}");
        }

        try {
            $this->{$handlerMethod}(...$arguments);
        } catch (Exception $exception) {
            // Ignore
        }
    }

    protected function routeMatchedHandler(RoutingEvents\RouteMatched $match): void
    {
        $routeAlias = $match->route->action['as'] ?? '';

        // Ignore the route if it is the route for the Laravel Folio package
        // We handle that route separately in the FolioPackageIntegration
        if ($routeAlias === 'laravel-folio') {
            return;
        }

        [$routeName] = Integration::extractNameAndSourceForRoute($match->route);

        Integration::addBreadcrumb(new Breadcrumb(
            Breadcrumb::LEVEL_INFO,
            Breadcrumb::TYPE_NAVIGATION,
            'route',
            $routeName
        ));

        Integration::setTransaction($routeName);
    }

    protected function queryExecutedHandler(DatabaseEvents\QueryExecuted $query): void
    {
        if (!$this->recordSqlQueries) {
            return;
        }

        $data = ['connectionName' => $query->connectionName];

        if ($query->time !== null) {
            $data['executionTimeMs'] = $query->time;
        }

        if ($this->recordSqlBindings) {
            $data['bindings'] = $query->bindings;
        }

        Integration::addBreadcrumb(new Breadcrumb(
            Breadcrumb::LEVEL_INFO,
            Breadcrumb::TYPE_DEFAULT,
            'db.sql.query',
            $query->sql,
            $data
        ));
    }

    protected function messageLoggedHandler(LogEvents\MessageLogged $logEntry): void
    {
        if (!$this->recordLaravelLogs) {
            return;
        }

        // A log message with `null` as value will not be recorded by Laravel
        // however empty strings are logged so we mimick that behaviour to
        // check for `null` to stay consistent with how Laravel logs it
        if ($logEntry->message === null) {
            return;
        }

        Integration::addBreadcrumb(new Breadcrumb(
            $this->logLevelToBreadcrumbLevel($logEntry->level),
            Breadcrumb::TYPE_DEFAULT,
            'log.' . $logEntry->level,
            $logEntry->message,
            $logEntry->context
        ));
    }

    protected function httpClientResponseReceivedHandler(HttpClientEvents\ResponseReceived $event): void
    {
        if (!$this->recordHttpClientRequests) {
            return;
        }

        $level = Breadcrumb::LEVEL_INFO;
        if ($event->response->failed()) {
            $level = Breadcrumb::LEVEL_ERROR;
        }

        $fullUri = $this->getFullUri($event->request->url());

        Integration::addBreadcrumb(new Breadcrumb(
            $level,
            Breadcrumb::TYPE_HTTP,
            'http',
            null,
            [
                'url' => $this->getPartialUri($fullUri),
                'http.request.method' => $event->request->method(),
                'http.response.status_code' => $event->response->status(),
                'http.query' => $fullUri->getQuery(),
                'http.fragment' => $fullUri->getFragment(),
                'http.request.body.size' => $event->request->toPsrRequest()->getBody()->getSize(),
                'http.response.body.size' => $event->response->toPsrResponse()->getBody()->getSize(),
            ]
        ));
    }

    protected function httpClientConnectionFailedHandler(HttpClientEvents\ConnectionFailed $event): void
    {
        if (!$this->recordHttpClientRequests) {
            return;
        }

        $fullUri = $this->getFullUri($event->request->url());

        Integration::addBreadcrumb(new Breadcrumb(
            Breadcrumb::LEVEL_ERROR,
            Breadcrumb::TYPE_HTTP,
            'http',
            null,
            [
                'url' => $this->getPartialUri($fullUri),
                'http.request.method' => $event->request->method(),
                'http.query' => $fullUri->getQuery(),
                'http.fragment' => $fullUri->getFragment(),
                'http.request.body.size' => $event->request->toPsrRequest()->getBody()->getSize(),
            ]
        ));
    }

    protected function authenticatedHandler(AuthEvents\Authenticated $event): void
    {
        $this->configureUserScopeFromModel($event->user);
    }

    protected function sanctumTokenAuthenticatedHandler(Sanctum\TokenAuthenticated $event): void
    {
        $this->configureUserScopeFromModel($event->token->tokenable);
    }

    /**
     * Configures the user scope with the user data and values from the HTTP request.
     *
     * @param mixed $authUser
     *
     * @return void
     */
    private function configureUserScopeFromModel($authUser): void
    {
        $userData = [];

        // If the user is a Laravel Eloquent model we try to extract some common fields from it
        if ($authUser instanceof Model) {
            $userData = [
                'id' => $authUser instanceof Authenticatable
                    ? $authUser->getAuthIdentifier()
                    : $authUser->getKey(),
                'email' => $authUser->getAttribute('email') ?? $authUser->getAttribute('mail'),
                'username' => $authUser->getAttribute('username'),
            ];
        }

        try {
            /** @var \Illuminate\Http\Request $request */
            $request = $this->container->make('request');

            if ($request instanceof Request) {
                $ipAddress = $request->ip();

                if ($ipAddress !== null) {
                    $userData['ip_address'] = $ipAddress;
                }
            }
        } catch (BindingResolutionException $e) {
            // If there is no request bound we cannot get the IP address from it
        }

        Integration::configureScope(static function (Scope $scope) use ($userData): void {
            $scope->setUser(array_filter($userData));
        });
    }

    protected function commandStartingHandler(ConsoleEvents\CommandStarting $event): void
    {
        if ($event->command) {
            Integration::configureScope(static function (Scope $scope) use ($event): void {
                $scope->setTag('command', $event->command);
            });

            if (!$this->recordCommandInfo) {
                return;
            }

            Integration::addBreadcrumb(new Breadcrumb(
                Breadcrumb::LEVEL_INFO,
                Breadcrumb::TYPE_DEFAULT,
                'artisan.command',
                'Starting Artisan command: ' . $event->command,
                [
                    'input' => $this->extractConsoleCommandInput($event->input),
                ]
            ));
        }
    }

    protected function commandFinishedHandler(ConsoleEvents\CommandFinished $event): void
    {
        if ($this->recordCommandInfo) {
            Integration::addBreadcrumb(new Breadcrumb(
                Breadcrumb::LEVEL_INFO,
                Breadcrumb::TYPE_DEFAULT,
                'artisan.command',
                'Finished Artisan command: ' . $event->command,
                [
                    'exit' => $event->exitCode,
                    'input' => $this->extractConsoleCommandInput($event->input),
                ]
            ));
        }

        // Flush any and all events that were possibly generated by the command
        Integration::flushEvents();

        Integration::configureScope(static function (Scope $scope): void {
            $scope->removeTag('command');
        });
    }

    /**
     * Extract the command input arguments if possible.
     *
     * @param \Symfony\Component\Console\Input\InputInterface|null $input
     *
     * @return string|null
     */
    private function extractConsoleCommandInput(?InputInterface $input): ?string
    {
        if ($input instanceof ArgvInput) {
            return (string)$input;
        }

        return null;
    }

    protected function octaneRequestReceivedHandler(Octane\RequestReceived $event): void
    {
        $this->prepareScopeForOctane();
    }

    protected function octaneRequestTerminatedHandler(Octane\RequestTerminated $event): void
    {
        $this->cleanupScopeForOctane();
    }

    protected function octaneTaskReceivedHandler(Octane\TaskReceived $event): void
    {
        $this->prepareScopeForOctane();

        if (!$this->recordOctaneTaskInfo) {
            return;
        }

        Integration::addBreadcrumb(new Breadcrumb(
            Breadcrumb::LEVEL_INFO,
            Breadcrumb::TYPE_DEFAULT,
            'octane.task',
            'Processing Octane task'
        ));
    }

    protected function octaneTaskTerminatedHandler(Octane\TaskTerminated $event): void
    {
        $this->cleanupScopeForOctane();
    }

    protected function octaneTickReceivedHandler(Octane\TickReceived $event): void
    {
        $this->prepareScopeForOctane();

        if (!$this->recordOctaneTickInfo) {
            return;
        }

        Integration::addBreadcrumb(new Breadcrumb(
            Breadcrumb::LEVEL_INFO,
            Breadcrumb::TYPE_DEFAULT,
            'octane.tick',
            'Processing Octane tick'
        ));
    }

    protected function octaneTickTerminatedHandler(Octane\TickTerminated $event): void
    {
        $this->cleanupScopeForOctane();
    }

    protected function octaneWorkerErrorOccurredHandler(Octane\WorkerErrorOccurred $event): void
    {
        $this->afterTaskWithinLongRunningProcess();
    }

    protected function octaneWorkerStoppingHandler(Octane\WorkerStopping $event): void
    {
        $this->afterTaskWithinLongRunningProcess();
    }

    private function prepareScopeForOctane(): void
    {
        $this->cleanupScopeForOctane();

        $this->prepareScopeForTaskWithinLongRunningProcess();

        $this->pushedOctaneScope = true;
    }

    private function cleanupScopeForOctane(): void
    {
        $this->cleanupScopeForTaskWithinLongRunningProcessWhen($this->pushedOctaneScope);

        $this->pushedOctaneScope = false;
    }

    /**
     * Translates common log levels to Sentry breadcrumb levels.
     *
     * @param string $level Log level. Maybe any standard.
     *
     * @return string Breadcrumb level.
     */
    private function logLevelToBreadcrumbLevel(string $level): string
    {
        switch (strtolower($level)) {
            case 'debug':
                return Breadcrumb::LEVEL_DEBUG;
            case 'warning':
                return Breadcrumb::LEVEL_WARNING;
            case 'error':
                return Breadcrumb::LEVEL_ERROR;
            case 'critical':
            case 'alert':
            case 'emergency':
                return Breadcrumb::LEVEL_FATAL;
            case 'info':
            case 'notice':
            default:
                return Breadcrumb::LEVEL_INFO;
        }
    }

    /**
     * Should be called after a task within a long running process has ended so events can be flushed.
     */
    private function afterTaskWithinLongRunningProcess(): void
    {
        Integration::flushEvents();
    }

    /**
     * Should be called before starting a task within a long running process, this is done to prevent
     * the task to have effect on the scope for the next task to run within the long running process.
     */
    private function prepareScopeForTaskWithinLongRunningProcess(): void
    {
        SentrySdk::getCurrentHub()->pushScope();

        // When a job starts, we want to make sure the scope is cleared of breadcrumbs
        SentrySdk::getCurrentHub()->configureScope(static function (Scope $scope) {
            $scope->clearBreadcrumbs();
        });
    }

    /**
     * Cleanup a previously prepared scope.
     *
     * @param bool $when Only cleanup the scope when this is true.
     *
     * @see prepareScopeForTaskWithinLongRunningProcess
     */
    private function cleanupScopeForTaskWithinLongRunningProcessWhen(bool $when): void
    {
        if (!$when) {
            return;
        }

        $this->afterTaskWithinLongRunningProcess();

        SentrySdk::getCurrentHub()->popScope();
    }
}
