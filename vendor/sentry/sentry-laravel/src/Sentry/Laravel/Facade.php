<?php

namespace Sentry\Laravel;

use Sentry\State\HubInterface;

/**
 * @see \Sentry\State\HubInterface
 *
 * @method static \Sentry\ClientInterface|null getClient()
 * @method static \Sentry\EventId|null getLastEventId()
 * @method static \Sentry\State\Scope pushScope()
 * @method static bool popScope()
 * @method static void withScope(callable $callback)
 * @method static void configureScope(callable $callback)
 * @method static void bindClient(\Sentry\ClientInterface $client)
 * @method static \Sentry\EventId|null captureMessage(string $message, \Sentry\Severity|null $level = null, \Sentry\EventHint|null $hint = null)
 * @method static \Sentry\EventId|null captureException(\Throwable $exception, \Sentry\EventHint|null $hint = null)
 * @method static \Sentry\EventId|null captureEvent(\Sentry\Event $event, \Sentry\EventHint|null $hint = null)
 * @method static \Sentry\EventId|null captureLastError(\Sentry\EventHint|null $hint = null)
 * @method static bool addBreadcrumb(\Sentry\Breadcrumb $breadcrumb)
 * @method static \Sentry\Integration\IntegrationInterface|null getIntegration(string $className)
 * @method static \Sentry\Tracing\Transaction startTransaction(\Sentry\Tracing\TransactionContext $context, array $customSamplingContext = [])
 * @method static \Sentry\Tracing\Transaction|null getTransaction()
 * @method static \Sentry\Tracing\Span|null getSpan()
 * @method static \Sentry\State\HubInterface setSpan(\Sentry\Tracing\Span|null $span)
 */
class Facade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return HubInterface::class;
    }
}
