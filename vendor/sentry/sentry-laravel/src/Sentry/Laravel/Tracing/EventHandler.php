<?php

namespace Sentry\Laravel\Tracing;

use Exception;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Events as DatabaseEvents;
use Illuminate\Http\Client\Events as HttpClientEvents;
use Illuminate\Routing\Events as RoutingEvents;
use RuntimeException;
use Sentry\Laravel\Features\Concerns\ResolvesEventOrigin;
use Sentry\Laravel\Integration;
use Sentry\Laravel\Util\WorksWithUris;
use Sentry\SentrySdk;
use Sentry\Tracing\Span;
use Sentry\Tracing\SpanContext;
use Sentry\Tracing\SpanStatus;
use Symfony\Component\HttpFoundation\Response;

class EventHandler
{
    use WorksWithUris, ResolvesEventOrigin;

    /**
     * Map event handlers to events.
     *
     * @var array
     */
    protected static $eventHandlerMap = [
        RoutingEvents\RouteMatched::class => 'routeMatched',
        DatabaseEvents\QueryExecuted::class => 'queryExecuted',
        RoutingEvents\ResponsePrepared::class => 'responsePrepared',
        RoutingEvents\PreparingResponse::class => 'responsePreparing',
        HttpClientEvents\RequestSending::class => 'httpClientRequestSending',
        HttpClientEvents\ResponseReceived::class => 'httpClientResponseReceived',
        HttpClientEvents\ConnectionFailed::class => 'httpClientConnectionFailed',
        DatabaseEvents\TransactionBeginning::class => 'transactionBeginning',
        DatabaseEvents\TransactionCommitted::class => 'transactionCommitted',
        DatabaseEvents\TransactionRolledBack::class => 'transactionRolledBack',
    ];

    /**
     * Indicates if we should we add SQL queries as spans.
     *
     * @var bool
     */
    private $traceSqlQueries;

    /**
     * Indicates if we should we add SQL query origin data to query spans.
     *
     * @var bool
     */
    private $traceSqlQueryOrigins;

    /**
     * Indicates if we should trace queue job spans.
     *
     * @var bool
     */
    private $traceQueueJobs;

    /**
     * Indicates if we should trace queue jobs as separate transactions.
     *
     * @var bool
     */
    private $traceQueueJobsAsTransactions;

    /**
     * Indicates if we should trace HTTP client requests.
     *
     * @var bool
     */
    private $traceHttpClientRequests;

    /**
     * Hold the stack of parent spans that need to be put back on the scope.
     *
     * @var array<int, Span|null>
     */
    private $parentSpanStack = [];

    /**
     * Hold the stack of current spans that need to be finished still.
     *
     * @var array<int, Span|null>
     */
    private $currentSpanStack = [];

    /**
     * EventHandler constructor.
     */
    public function __construct(array $config)
    {
        $this->traceSqlQueries = ($config['sql_queries'] ?? true) === true;
        $this->traceSqlQueryOrigins = ($config['sql_origin'] ?? true) === true;

        $this->traceHttpClientRequests = ($config['http_client_requests'] ?? true) === true;

        $this->traceQueueJobs = ($config['queue_jobs'] ?? false) === true;
        $this->traceQueueJobsAsTransactions = ($config['queue_job_transactions'] ?? false) === true;
    }

    /**
     * Attach all event handlers.
     *
     * @uses self::routeMatchedHandler()
     * @uses self::queryExecutedHandler()
     * @uses self::responsePreparedHandler()
     * @uses self::responsePreparingHandler()
     * @uses self::transactionBeginningHandler()
     * @uses self::transactionCommittedHandler()
     * @uses self::transactionRolledBackHandler()
     * @uses self::httpClientRequestSendingHandler()
     * @uses self::httpClientResponseReceivedHandler()
     * @uses self::httpClientConnectionFailedHandler()
     */
    public function subscribe(Dispatcher $dispatcher): void
    {
        foreach (static::$eventHandlerMap as $eventName => $handler) {
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
            throw new RuntimeException("Missing tracing event handler: {$handlerMethod}");
        }

        try {
            $this->{$handlerMethod}(...$arguments);
        } catch (Exception $e) {
            // Ignore to prevent bubbling up errors in the SDK
        }
    }

    protected function routeMatchedHandler(RoutingEvents\RouteMatched $match): void
    {
        $transaction = SentrySdk::getCurrentHub()->getTransaction();

        if ($transaction === null) {
            return;
        }

        [$transactionName, $transactionSource] = Integration::extractNameAndSourceForRoute($match->route);

        $transaction->setName($transactionName);
        $transaction->getMetadata()->setSource($transactionSource);
    }

    protected function queryExecutedHandler(DatabaseEvents\QueryExecuted $query): void
    {
        if (!$this->traceSqlQueries) {
            return;
        }

        $parentSpan = SentrySdk::getCurrentHub()->getSpan();

        // If there is no tracing span active there is no need to handle the event
        if ($parentSpan === null) {
            return;
        }

        $context = new SpanContext();
        $context->setOp('db.sql.query');
        $context->setDescription($query->sql);
        $context->setData([
            'db.name' => $query->connection->getDatabaseName(),
            'db.system' => $query->connection->getDriverName(),
            'server.address' => $query->connection->getConfig('host'),
            'server.port' => $query->connection->getConfig('port'),
        ]);
        $context->setStartTimestamp(microtime(true) - $query->time / 1000);
        $context->setEndTimestamp($context->getStartTimestamp() + $query->time / 1000);

        if ($this->traceSqlQueryOrigins) {
            $queryOrigin = $this->resolveQueryOriginFromBacktrace();

            if ($queryOrigin !== null) {
                $context->setData(array_merge($context->getData(), [
                    'db.sql.origin' => $queryOrigin
                ]));
            }
        }

        $parentSpan->startChild($context);
    }

    /**
     * Try to find the origin of the SQL query that was just executed.
     *
     * @return string|null
     */
    private function resolveQueryOriginFromBacktrace(): ?string
    {
        $backtraceHelper = $this->makeBacktraceHelper();

        $firstAppFrame = $backtraceHelper->findFirstInAppFrameForBacktrace(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));

        if ($firstAppFrame === null) {
            return null;
        }

        $filePath = $backtraceHelper->getOriginalViewPathForFrameOfCompiledViewPath($firstAppFrame) ?? $firstAppFrame->getFile();

        return "{$filePath}:{$firstAppFrame->getLine()}";
    }

    protected function responsePreparedHandler(RoutingEvents\ResponsePrepared $event): void
    {
        $span = $this->popSpan();

        if ($span !== null) {
            $span->finish();
        }
    }

    protected function responsePreparingHandler(RoutingEvents\PreparingResponse $event): void
    {
        // If the response is already a Response object there is no need to handle the event anymore
        // since there isn't going to be any real work going on, the response is already as prepared
        // as it can be. So we ignore the event to prevent loggin a very short empty duplicated span
        if ($event->response instanceof Response) {
            return;
        }

        $parentSpan = SentrySdk::getCurrentHub()->getSpan();

        // If there is no tracing span active there is no need to handle the event
        if ($parentSpan === null) {
            return;
        }

        $context = new SpanContext;
        $context->setOp('http.route.response');

        $this->pushSpan($parentSpan->startChild($context));
    }

    protected function transactionBeginningHandler(DatabaseEvents\TransactionBeginning $event): void
    {
        $parentSpan = SentrySdk::getCurrentHub()->getSpan();

        // If there is no tracing span active there is no need to handle the event
        if ($parentSpan === null) {
            return;
        }

        $context = new SpanContext;
        $context->setOp('db.transaction');

        $this->pushSpan($parentSpan->startChild($context));
    }

    protected function transactionCommittedHandler(DatabaseEvents\TransactionCommitted $event): void
    {
        $span = $this->popSpan();

        if ($span !== null) {
            $span->finish();
            $span->setStatus(SpanStatus::ok());
        }
    }

    protected function transactionRolledBackHandler(DatabaseEvents\TransactionRolledBack $event): void
    {
        $span = $this->popSpan();

        if ($span !== null) {
            $span->finish();
            $span->setStatus(SpanStatus::internalError());
        }
    }

    protected function httpClientRequestSendingHandler(HttpClientEvents\RequestSending $event): void
    {
        if (!$this->traceHttpClientRequests) {
            return;
        }

        $parentSpan = SentrySdk::getCurrentHub()->getSpan();

        // If there is no tracing span active there is no need to handle the event
        if ($parentSpan === null) {
            return;
        }

        $context = new SpanContext;

        $fullUri = $this->getFullUri($event->request->url());
        $partialUri = $this->getPartialUri($fullUri);

        $context->setOp('http.client');
        $context->setDescription($event->request->method() . ' ' . $partialUri);
        $context->setData([
            'url' => $partialUri,
            'http.request.method' => $event->request->method(),
            'http.query' => $fullUri->getQuery(),
            'http.fragment' => $fullUri->getFragment(),
        ]);

        $this->pushSpan($parentSpan->startChild($context));
    }

    protected function httpClientResponseReceivedHandler(HttpClientEvents\ResponseReceived $event): void
    {
        if (!$this->traceHttpClientRequests) {
            return;
        }

        $span = $this->popSpan();

        if ($span !== null) {
            $span->finish();
            $span->setHttpStatus($event->response->status());
        }
    }

    protected function httpClientConnectionFailedHandler(HttpClientEvents\ConnectionFailed $event): void
    {
        if (!$this->traceHttpClientRequests) {
            return;
        }

        $span = $this->popSpan();

        if ($span !== null) {
            $span->finish();
            $span->setStatus(SpanStatus::internalError());
        }
    }

    private function pushSpan(Span $span): void
    {
        $hub = SentrySdk::getCurrentHub();

        $this->parentSpanStack[] = $hub->getSpan();

        $hub->setSpan($span);

        $this->currentSpanStack[] = $span;
    }

    private function popSpan(): ?Span
    {
        if (count($this->currentSpanStack) === 0) {
            return null;
        }

        $parent = array_pop($this->parentSpanStack);

        SentrySdk::getCurrentHub()->setSpan($parent);

        return array_pop($this->currentSpanStack);
    }
}
