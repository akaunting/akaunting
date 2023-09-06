<?php

declare(strict_types=1);

namespace Sentry;

use Sentry\State\Scope;
use Sentry\Tracing\PropagationContext;
use Sentry\Tracing\SpanContext;
use Sentry\Tracing\Transaction;
use Sentry\Tracing\TransactionContext;

/**
 * Creates a new Client and Hub which will be set as current.
 *
 * @param array<string, mixed> $options The client options
 */
function init(array $options = []): void
{
    $client = ClientBuilder::create($options)->getClient();

    SentrySdk::init()->bindClient($client);
}

/**
 * Captures a message event and sends it to Sentry.
 *
 * @param string         $message The message
 * @param Severity|null  $level   The severity level of the message
 * @param EventHint|null $hint    Object that can contain additional information about the event
 */
function captureMessage(string $message, ?Severity $level = null, ?EventHint $hint = null): ?EventId
{
    return SentrySdk::getCurrentHub()->captureMessage($message, $level, $hint);
}

/**
 * Captures an exception event and sends it to Sentry.
 *
 * @param \Throwable     $exception The exception
 * @param EventHint|null $hint      Object that can contain additional information about the event
 */
function captureException(\Throwable $exception, ?EventHint $hint = null): ?EventId
{
    return SentrySdk::getCurrentHub()->captureException($exception, $hint);
}

/**
 * Captures a new event using the provided data.
 *
 * @param Event          $event The event being captured
 * @param EventHint|null $hint  May contain additional information about the event
 */
function captureEvent(Event $event, ?EventHint $hint = null): ?EventId
{
    return SentrySdk::getCurrentHub()->captureEvent($event, $hint);
}

/**
 * Logs the most recent error (obtained with {@see error_get_last()}).
 *
 * @param EventHint|null $hint Object that can contain additional information about the event
 */
function captureLastError(?EventHint $hint = null): ?EventId
{
    return SentrySdk::getCurrentHub()->captureLastError($hint);
}

/**
 * Captures a check-in and sends it to Sentry.
 *
 * @param string             $slug          Identifier of the Monitor
 * @param CheckInStatus      $status        The status of the check-in
 * @param int|float|null     $duration      The duration of the check-in
 * @param MonitorConfig|null $monitorConfig Configuration of the Monitor
 * @param string|null        $checkInId     A check-in ID from the previous check-in
 */
function captureCheckIn(string $slug, CheckInStatus $status, $duration = null, ?MonitorConfig $monitorConfig = null, ?string $checkInId = null): ?string
{
    return SentrySdk::getCurrentHub()->captureCheckIn($slug, $status, $duration, $monitorConfig, $checkInId);
}

/**
 * Records a new breadcrumb which will be attached to future events. They
 * will be added to subsequent events to provide more context on user's
 * actions prior to an error or crash.
 *
 * @param Breadcrumb $breadcrumb The breadcrumb to record
 */
function addBreadcrumb(Breadcrumb $breadcrumb): void
{
    SentrySdk::getCurrentHub()->addBreadcrumb($breadcrumb);
}

/**
 * Calls the given callback passing to it the current scope so that any
 * operation can be run within its context.
 *
 * @param callable $callback The callback to be executed
 */
function configureScope(callable $callback): void
{
    SentrySdk::getCurrentHub()->configureScope($callback);
}

/**
 * Creates a new scope with and executes the given operation within. The scope
 * is automatically removed once the operation finishes or throws.
 *
 * @param callable $callback The callback to be executed
 *
 * @return mixed|void The callback's return value, upon successful execution
 *
 * @psalm-template T
 *
 * @psalm-param callable(Scope): T $callback
 *
 * @psalm-return T
 */
function withScope(callable $callback)
{
    return SentrySdk::getCurrentHub()->withScope($callback);
}

/**
 * Starts a new `Transaction` and returns it. This is the entry point to manual
 * tracing instrumentation.
 *
 * A tree structure can be built by adding child spans to the transaction, and
 * child spans to other spans. To start a new child span within the transaction
 * or any span, call the respective `startChild()` method.
 *
 * Every child span must be finished before the transaction is finished,
 * otherwise the unfinished spans are discarded.
 *
 * The transaction must be finished with a call to its `finish()` method, at
 * which point the transaction with all its finished child spans will be sent to
 * Sentry.
 *
 * @param TransactionContext   $context               Properties of the new transaction
 * @param array<string, mixed> $customSamplingContext Additional context that will be passed to the {@see \Sentry\Tracing\SamplingContext}
 */
function startTransaction(TransactionContext $context, array $customSamplingContext = []): Transaction
{
    return SentrySdk::getCurrentHub()->startTransaction($context, $customSamplingContext);
}

/**
 * Execute the given callable while wrapping it in a span added as a child to the current transaction and active span.
 *
 * If there is no transaction active this is a no-op and the scope passed to the trace callable will be unused.
 *
 * @template T
 *
 * @param callable(Scope): T $trace   The callable that is going to be traced
 * @param SpanContext        $context The context of the span to be created
 *
 * @return T
 */
function trace(callable $trace, SpanContext $context)
{
    return SentrySdk::getCurrentHub()->withScope(function (Scope $scope) use ($context, $trace) {
        $parentSpan = $scope->getSpan();

        // If there's a span set on the scope there is a transaction
        // active currently. If that is the case we create a child span
        // and set it on the scope. Otherwise we only execute the callable
        if (null !== $parentSpan) {
            $span = $parentSpan->startChild($context);

            $scope->setSpan($span);
        }

        try {
            return $trace($scope);
        } finally {
            if (isset($span)) {
                $span->finish();

                $scope->setSpan($parentSpan);
            }
        }
    });
}

/**
 * Creates the current traceparent string, to be used as a HTTP header value
 * or HTML meta tag value.
 * This function is context aware, as in it either returns the traceparent based
 * on the current span, or the scope's propagation context.
 */
function getTraceparent(): string
{
    $hub = SentrySdk::getCurrentHub();
    $client = $hub->getClient();

    if (null !== $client) {
        $options = $client->getOptions();

        if (null !== $options && $options->isTracingEnabled()) {
            $span = SentrySdk::getCurrentHub()->getSpan();
            if (null !== $span) {
                return $span->toTraceparent();
            }
        }
    }

    $traceParent = '';
    $hub->configureScope(function (Scope $scope) use (&$traceParent) {
        $traceParent = $scope->getPropagationContext()->toTraceparent();
    });

    return $traceParent;
}

/**
 * Creates the baggage content string, to be used as a HTTP header value
 * or HTML meta tag value.
 * This function is context aware, as in it either returns the baggage based
 * on the current span or the scope's propagation context.
 */
function getBaggage(): string
{
    $hub = SentrySdk::getCurrentHub();
    $client = $hub->getClient();

    if (null !== $client) {
        $options = $client->getOptions();

        if (null !== $options && $options->isTracingEnabled()) {
            $span = SentrySdk::getCurrentHub()->getSpan();
            if (null !== $span) {
                return $span->toBaggage();
            }
        }
    }

    $baggage = '';
    $hub->configureScope(function (Scope $scope) use (&$baggage) {
        $baggage = $scope->getPropagationContext()->toBaggage();
    });

    return $baggage;
}

/**
 * Continue a trace based on HTTP header values.
 * If the SDK is configured with enabled tracing,
 * this function returns a populated TransactionContext.
 * In any other cases, it populates the propagation context on the scope.
 */
function continueTrace(string $sentryTrace, string $baggage): TransactionContext
{
    $hub = SentrySdk::getCurrentHub();
    $hub->configureScope(function (Scope $scope) use ($sentryTrace, $baggage) {
        $propagationContext = PropagationContext::fromHeaders($sentryTrace, $baggage);
        $scope->setPropagationContext($propagationContext);
    });

    return TransactionContext::fromHeaders($sentryTrace, $baggage);
}
