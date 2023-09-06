<?php

namespace Sentry\Laravel\Tracing;

use Closure;
use Illuminate\Contracts\Foundation\Application as LaravelApplication;
use Illuminate\Http\Request;
use Laravel\Lumen\Application as LumenApplication;
use Sentry\SentrySdk;
use Sentry\State\HubInterface;
use Sentry\Tracing\Span;
use Sentry\Tracing\SpanContext;
use Sentry\Tracing\TransactionSource;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

use function Sentry\continueTrace;

class Middleware
{
    /**
     * The current active transaction.
     *
     * @var \Sentry\Tracing\Transaction|null
     */
    protected $transaction;

    /**
     * The span for the `app.handle` part of the application.
     *
     * @var \Sentry\Tracing\Span|null
     */
    protected $appSpan;

    /**
     * The timestamp of application bootstrap completion.
     *
     * @var float|null
     */
    private $bootedTimestamp;

    /**
     * The Laravel or Lumen application instance.
     *
     * @var LaravelApplication|LumenApplication
     */
    private $app;

    /**
     * Whether we should continue tracing after the response has been sent to the client.
     *
     * @var bool
     */
    private $continueAfterResponse;

    /**
     * Whether the terminating callback has been registered.
     *
     * @var bool
     */
    private $registeredTerminatingCallback = false;

    /**
     * Construct the Sentry tracing middleware.
     *
     * @param LaravelApplication|LumenApplication $app
     */
    public function __construct($app, bool $continueAfterResponse = true)
    {
        $this->app = $app;
        $this->continueAfterResponse = $continueAfterResponse;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (app()->bound(HubInterface::class)) {
            $this->startTransaction($request, app(HubInterface::class));
        }

        return $next($request);
    }

    /**
     * Handle the application termination.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed                    $response
     *
     * @return void
     */
    public function terminate(Request $request, $response): void
    {
        // If there is no transaction or the HubInterface is not bound in the container there is nothing for us to do
        if ($this->transaction === null || !app()->bound(HubInterface::class)) {
            return;
        }

        // We stop here if a route has not been matched unless we are configured to trace missing routes
        if (config('sentry.tracing.missing_routes', false) === false && $request->route() === null) {
            return;
        }

        if ($this->appSpan !== null) {
            $this->appSpan->finish();
            $this->appSpan = null;
        }

        if ($response instanceof SymfonyResponse) {
            $this->hydrateResponseData($response);
        }

        if ($this->continueAfterResponse) {
            // Ensure we do not register the terminating callback multiple times since there is no point in doing so
            if ($this->registeredTerminatingCallback) {
                return;
            }

            // We need to finish the transaction after the response has been sent to the client
            // so we register a terminating callback to do so, this allows us to also capture
            // spans that are created during the termination of the application like queue
            // dispatched using dispatch(...)->afterResponse(). This middleware is called
            // before the terminating callbacks so we are 99.9% sure to be the last one
            // to run except if another terminating callback is registered after ours.
            $this->app->terminating(function () {
                $this->finishTransaction();
            });

            $this->registeredTerminatingCallback = true;
        } else {
            $this->finishTransaction();
        }
    }

    /**
     * Set the timestamp of application bootstrap completion.
     *
     * @param float|null $timestamp The unix timestamp of the booted event, default to `microtime(true)` if not `null`.
     *
     * @return void
     *
     * @internal This method should only be invoked right after the application has finished "booting".
     */
    public function setBootedTimestamp(?float $timestamp = null): void
    {
        $this->bootedTimestamp = $timestamp ?? microtime(true);
    }

    private function startTransaction(Request $request, HubInterface $sentry): void
    {
        // Try $_SERVER['REQUEST_TIME_FLOAT'] then LARAVEL_START and fallback to microtime(true) if neither are defined
        $requestStartTime = $request->server(
            'REQUEST_TIME_FLOAT',
            defined('LARAVEL_START')
                ? LARAVEL_START
                : microtime(true)
        );

        $context = continueTrace(
            $request->header('sentry-trace', ''),
            $request->header('baggage', '')
        );

        $requestPath = '/' . ltrim($request->path(), '/');

        $context->setOp('http.server');
        $context->setName($requestPath);
        $context->setSource(TransactionSource::url());
        $context->setStartTimestamp($requestStartTime);

        $context->setData([
            'url' => $requestPath,
            'http.request.method' => strtoupper($request->method()),
        ]);

        $transaction = $sentry->startTransaction($context);

        // If this transaction is not sampled, we can stop here to prevent doing work for nothing
        if (!$transaction->getSampled()) {
            return;
        }

        $this->transaction = $transaction;

        SentrySdk::getCurrentHub()->setSpan($this->transaction);

        $bootstrapSpan = $this->addAppBootstrapSpan();

        $appContextStart = new SpanContext;
        $appContextStart->setOp('middleware.handle');
        $appContextStart->setStartTimestamp($bootstrapSpan ? $bootstrapSpan->getEndTimestamp() : microtime(true));

        $this->appSpan = $this->transaction->startChild($appContextStart);

        SentrySdk::getCurrentHub()->setSpan($this->appSpan);
    }

    private function addAppBootstrapSpan(): ?Span
    {
        if ($this->bootedTimestamp === null) {
            return null;
        }

        $spanContextStart = new SpanContext;
        $spanContextStart->setOp('app.bootstrap');
        $spanContextStart->setStartTimestamp($this->transaction->getStartTimestamp());
        $spanContextStart->setEndTimestamp($this->bootedTimestamp);

        $span = $this->transaction->startChild($spanContextStart);

        // Consume the booted timestamp, because we don't want to report the bootstrap span more than once
        $this->bootedTimestamp = null;

        // Add more information about the bootstrap section if possible
        $this->addBootDetailTimeSpans($span);

        return $span;
    }

    private function addBootDetailTimeSpans(Span $bootstrap): void
    {
        // This constant should be defined right after the composer `autoload.php` require statement in `public/index.php`
        // define('SENTRY_AUTOLOAD', microtime(true));
        if (!defined('SENTRY_AUTOLOAD') || !SENTRY_AUTOLOAD) {
            return;
        }

        $autoload = new SpanContext;
        $autoload->setOp('app.php.autoload');
        $autoload->setStartTimestamp($this->transaction->getStartTimestamp());
        $autoload->setEndTimestamp(SENTRY_AUTOLOAD);

        $bootstrap->startChild($autoload);
    }

    private function hydrateResponseData(SymfonyResponse $response): void
    {
        $this->transaction->setHttpStatus($response->getStatusCode());
    }

    private function finishTransaction(): void
    {
        // We could end up multiple times here since we register a terminating callback so
        // double check if we have a transaction before trying to finish it since it could
        // have already been finished in between being registered and being executed again
        if ($this->transaction === null) {
            return;
        }

        // Make sure we set the transaction and not have a child span in the Sentry SDK
        // If the transaction is not on the scope during finish, the trace.context is wrong
        SentrySdk::getCurrentHub()->setSpan($this->transaction);

        $this->transaction->finish();
        $this->transaction = null;
    }
}
