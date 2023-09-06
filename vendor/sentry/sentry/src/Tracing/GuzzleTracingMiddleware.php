<?php

declare(strict_types=1);

namespace Sentry\Tracing;

use Closure;
use GuzzleHttp\Exception\RequestException as GuzzleRequestException;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Sentry\Breadcrumb;
use Sentry\ClientInterface;
use Sentry\SentrySdk;
use Sentry\State\HubInterface;
use function Sentry\getBaggage;
use function Sentry\getTraceparent;

/**
 * This handler traces each outgoing HTTP request by recording performance data.
 */
final class GuzzleTracingMiddleware
{
    public static function trace(?HubInterface $hub = null): Closure
    {
        return static function (callable $handler) use ($hub): Closure {
            return static function (RequestInterface $request, array $options) use ($hub, $handler) {
                $hub = $hub ?? SentrySdk::getCurrentHub();
                $client = $hub->getClient();
                $span = $hub->getSpan();

                if (null === $span) {
                    if (self::shouldAttachTracingHeaders($client, $request)) {
                        $request = $request
                            ->withHeader('sentry-trace', getTraceparent())
                            ->withHeader('baggage', getBaggage());
                    }

                    return $handler($request, $options);
                }

                $partialUri = Uri::fromParts([
                    'scheme' => $request->getUri()->getScheme(),
                    'host' => $request->getUri()->getHost(),
                    'port' => $request->getUri()->getPort(),
                    'path' => $request->getUri()->getPath(),
                ]);

                $spanContext = new SpanContext();
                $spanContext->setOp('http.client');
                $spanContext->setDescription($request->getMethod() . ' ' . (string) $partialUri);
                $spanContext->setData([
                    'http.query' => $request->getUri()->getQuery(),
                    'http.fragment' => $request->getUri()->getFragment(),
                ]);

                $childSpan = $span->startChild($spanContext);

                if (self::shouldAttachTracingHeaders($client, $request)) {
                    $request = $request
                        ->withHeader('sentry-trace', $childSpan->toTraceparent())
                        ->withHeader('baggage', $childSpan->toBaggage());
                }

                $handlerPromiseCallback = static function ($responseOrException) use ($hub, $request, $childSpan, $partialUri) {
                    // We finish the span (which means setting the span end timestamp) first to ensure the measured time
                    // the span spans is as close to only the HTTP request time and do the data collection afterwards
                    $childSpan->finish();

                    $response = null;

                    /** @psalm-suppress UndefinedClass */
                    if ($responseOrException instanceof ResponseInterface) {
                        $response = $responseOrException;
                    } elseif ($responseOrException instanceof GuzzleRequestException) {
                        $response = $responseOrException->getResponse();
                    }

                    $breadcrumbData = [
                        'url' => (string) $partialUri,
                        'method' => $request->getMethod(),
                        'request_body_size' => $request->getBody()->getSize(),
                        'http.query' => $request->getUri()->getQuery(),
                        'http.fragment' => $request->getUri()->getFragment(),
                    ];

                    if (null !== $response) {
                        $childSpan->setStatus(SpanStatus::createFromHttpStatusCode($response->getStatusCode()));

                        $breadcrumbData['status_code'] = $response->getStatusCode();
                        $breadcrumbData['response_body_size'] = $response->getBody()->getSize();
                    } else {
                        $childSpan->setStatus(SpanStatus::internalError());
                    }

                    $hub->addBreadcrumb(new Breadcrumb(
                        Breadcrumb::LEVEL_INFO,
                        Breadcrumb::TYPE_HTTP,
                        'http',
                        null,
                        $breadcrumbData
                    ));

                    if ($responseOrException instanceof \Throwable) {
                        throw $responseOrException;
                    }

                    return $responseOrException;
                };

                return $handler($request, $options)->then($handlerPromiseCallback, $handlerPromiseCallback);
            };
        };
    }

    private static function shouldAttachTracingHeaders(?ClientInterface $client, RequestInterface $request): bool
    {
        if (null !== $client) {
            $sdkOptions = $client->getOptions();

            // Check if the request destination is allow listed in the trace_propagation_targets option.
            if (
                null !== $sdkOptions->getTracePropagationTargets() &&
                // Due to BC, we treat an empty array (the default) as all hosts are allow listed
                (
                    [] === $sdkOptions->getTracePropagationTargets() ||
                    \in_array($request->getUri()->getHost(), $sdkOptions->getTracePropagationTargets())
                )
            ) {
                return true;
            }
        }

        return false;
    }
}
