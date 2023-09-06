<?php

declare(strict_types=1);

namespace Sentry\Tracing;

use Sentry\SentrySdk;
use Sentry\State\Scope;

final class PropagationContext
{
    private const TRACEPARENT_HEADER_REGEX = '/^[ \\t]*(?<trace_id>[0-9a-f]{32})?-?(?<span_id>[0-9a-f]{16})?-?(?<sampled>[01])?[ \\t]*$/i';

    /**
     * @var TraceId The trace id
     */
    private $traceId;

    /**
     * @var SpanId The span id
     */
    private $spanId;

    /**
     * @var SpanId|null The parent span id
     */
    private $parentSpanId;

    /**
     * @var DynamicSamplingContext|null The dynamic sampling context
     */
    private $dynamicSamplingContext;

    private function __construct()
    {
    }

    public static function fromDefaults(): self
    {
        $context = new self();

        $context->traceId = TraceId::generate();
        $context->spanId = SpanId::generate();
        $context->parentSpanId = null;
        $context->dynamicSamplingContext = null;

        return $context;
    }

    public static function fromHeaders(string $sentryTraceHeader, string $baggageHeader): self
    {
        return self::parseTraceAndBaggage($sentryTraceHeader, $baggageHeader);
    }

    public static function fromEnvironment(string $sentryTrace, string $baggage): self
    {
        return self::parseTraceAndBaggage($sentryTrace, $baggage);
    }

    /**
     * Returns a string that can be used for the `sentry-trace` header & meta tag.
     */
    public function toTraceparent(): string
    {
        return sprintf('%s-%s', (string) $this->traceId, (string) $this->spanId);
    }

    /**
     * Returns a string that can be used for the `baggage` header & meta tag.
     */
    public function toBaggage(): string
    {
        if (null === $this->dynamicSamplingContext) {
            $hub = SentrySdk::getCurrentHub();
            $client = $hub->getClient();

            if (null !== $client) {
                $options = $client->getOptions();

                if (null !== $options) {
                    $hub->configureScope(function (Scope $scope) use ($options) {
                        $this->dynamicSamplingContext = DynamicSamplingContext::fromOptions($options, $scope);
                    });
                }
            }
        }

        return (string) $this->dynamicSamplingContext;
    }

    /**
     * @return array<string, mixed>
     */
    public function getTraceContext(): array
    {
        $result = [
            'trace_id' => (string) $this->traceId,
            'span_id' => (string) $this->spanId,
        ];

        if (null !== $this->parentSpanId) {
            $result['parent_span_id'] = (string) $this->parentSpanId;
        }

        return $result;
    }

    public function getTraceId(): TraceId
    {
        return $this->traceId;
    }

    public function setTraceId(TraceId $traceId): void
    {
        $this->traceId = $traceId;
    }

    public function getParentSpanId(): ?SpanId
    {
        return $this->parentSpanId;
    }

    public function setParentSpanId(?SpanId $parentSpanId): void
    {
        $this->parentSpanId = $parentSpanId;
    }

    public function getSpanId(): SpanId
    {
        return $this->spanId;
    }

    public function setSpanId(SpanId $spanId): void
    {
        $this->spanId = $spanId;
    }

    public function getDynamicSamplingContext(): ?DynamicSamplingContext
    {
        return $this->dynamicSamplingContext;
    }

    public function setDynamicSamplingContext(DynamicSamplingContext $dynamicSamplingContext): void
    {
        $this->dynamicSamplingContext = $dynamicSamplingContext;
    }

    private static function parseTraceAndBaggage(string $sentryTrace, string $baggage): self
    {
        $context = self::fromDefaults();
        $hasSentryTrace = false;

        if (preg_match(self::TRACEPARENT_HEADER_REGEX, $sentryTrace, $matches)) {
            if (!empty($matches['trace_id'])) {
                $context->traceId = new TraceId($matches['trace_id']);
                $hasSentryTrace = true;
            }

            if (!empty($matches['span_id'])) {
                $context->parentSpanId = new SpanId($matches['span_id']);
                $hasSentryTrace = true;
            }
        }

        $samplingContext = DynamicSamplingContext::fromHeader($baggage);

        if ($hasSentryTrace && !$samplingContext->hasEntries()) {
            // The request comes from an old SDK which does not support Dynamic Sampling.
            // Propagate the Dynamic Sampling Context as is, but frozen, even without sentry-* entries.
            $samplingContext->freeze();
            $context->dynamicSamplingContext = $samplingContext;
        }

        if ($hasSentryTrace && $samplingContext->hasEntries()) {
            // The baggage header contains Dynamic Sampling Context data from an upstream SDK.
            // Propagate this Dynamic Sampling Context.
            $context->dynamicSamplingContext = $samplingContext;
        }

        return $context;
    }
}
