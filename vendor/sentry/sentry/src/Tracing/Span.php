<?php

declare(strict_types=1);

namespace Sentry\Tracing;

use Sentry\EventId;

/**
 * This class stores all the information about a span.
 */
class Span
{
    /**
     * @var SpanId Span ID
     */
    protected $spanId;

    /**
     * @var TraceId Trace ID
     */
    protected $traceId;

    /**
     * @var string|null Description of the span
     */
    protected $description;

    /**
     * @var string|null Operation of the span
     */
    protected $op;

    /**
     * @var SpanStatus|null Completion status of the span
     */
    protected $status;

    /**
     * @var SpanId|null ID of the parent span
     */
    protected $parentSpanId;

    /**
     * @var bool|null Has the sample decision been made?
     */
    protected $sampled;

    /**
     * @var array<string, string> A List of tags associated to this span
     */
    protected $tags = [];

    /**
     * @var array<string, mixed> An arbitrary mapping of additional metadata
     */
    protected $data = [];

    /**
     * @var float Timestamp in seconds (epoch time) indicating when the span started
     */
    protected $startTimestamp;

    /**
     * @var float|null Timestamp in seconds (epoch time) indicating when the span ended
     */
    protected $endTimestamp;

    /**
     * @var SpanRecorder|null Reference instance to the {@see SpanRecorder}
     */
    protected $spanRecorder;

    /**
     * @var Transaction|null The transaction containing this span
     */
    protected $transaction;

    /**
     * Constructor.
     *
     * @param SpanContext|null $context The context to create the span with
     *
     * @internal
     */
    public function __construct(?SpanContext $context = null)
    {
        if (null === $context) {
            $this->traceId = TraceId::generate();
            $this->spanId = SpanId::generate();
            $this->startTimestamp = microtime(true);

            return;
        }

        $this->traceId = $context->getTraceId() ?? TraceId::generate();
        $this->spanId = $context->getSpanId() ?? SpanId::generate();
        $this->startTimestamp = $context->getStartTimestamp() ?? microtime(true);
        $this->parentSpanId = $context->getParentSpanId();
        $this->description = $context->getDescription();
        $this->op = $context->getOp();
        $this->status = $context->getStatus();
        $this->sampled = $context->getSampled();
        $this->tags = $context->getTags();
        $this->data = $context->getData();
        $this->endTimestamp = $context->getEndTimestamp();
    }

    /**
     * Sets the ID of the span.
     *
     * @param SpanId $spanId The ID
     */
    public function setSpanId(SpanId $spanId): void
    {
        $this->spanId = $spanId;
    }

    /**
     * Gets the ID that determines which trace the span belongs to.
     */
    public function getTraceId(): TraceId
    {
        return $this->traceId;
    }

    /**
     * Sets the ID that determines which trace the span belongs to.
     *
     * @param TraceId $traceId The ID
     */
    public function setTraceId(TraceId $traceId): void
    {
        $this->traceId = $traceId;
    }

    /**
     * Gets the ID that determines which span is the parent of the current one.
     */
    public function getParentSpanId(): ?SpanId
    {
        return $this->parentSpanId;
    }

    /**
     * Sets the ID that determines which span is the parent of the current one.
     *
     * @param SpanId|null $parentSpanId The ID
     */
    public function setParentSpanId(?SpanId $parentSpanId): void
    {
        $this->parentSpanId = $parentSpanId;
    }

    /**
     * Gets the timestamp representing when the measuring started.
     */
    public function getStartTimestamp(): float
    {
        return $this->startTimestamp;
    }

    /**
     * Sets the timestamp representing when the measuring started.
     *
     * @param float $startTimestamp The timestamp
     */
    public function setStartTimestamp(float $startTimestamp): void
    {
        $this->startTimestamp = $startTimestamp;
    }

    /**
     * Gets the timestamp representing when the measuring finished.
     */
    public function getEndTimestamp(): ?float
    {
        return $this->endTimestamp;
    }

    /**
     * Gets a description of the span's operation, which uniquely identifies
     * the span but is consistent across instances of the span.
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Sets a description of the span's operation, which uniquely identifies
     * the span but is consistent across instances of the span.
     *
     * @param string|null $description The description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * Gets a short code identifying the type of operation the span is measuring.
     */
    public function getOp(): ?string
    {
        return $this->op;
    }

    /**
     * Sets a short code identifying the type of operation the span is measuring.
     *
     * @param string|null $op The short code
     */
    public function setOp(?string $op): void
    {
        $this->op = $op;
    }

    /**
     * Gets the status of the span/transaction.
     */
    public function getStatus(): ?SpanStatus
    {
        return $this->status;
    }

    /**
     * Sets the status of the span/transaction.
     *
     * @param SpanStatus|null $status The status
     */
    public function setStatus(?SpanStatus $status): void
    {
        $this->status = $status;
    }

    /**
     * Sets the HTTP status code and the status of the span/transaction.
     *
     * @param int $statusCode The HTTP status code
     */
    public function setHttpStatus(int $statusCode): void
    {
        $this->tags['http.status_code'] = (string) $statusCode;

        $status = SpanStatus::createFromHttpStatusCode($statusCode);

        if ($status !== SpanStatus::unknownError()) {
            $this->status = $status;
        }
    }

    /**
     * Gets a map of tags for this event.
     *
     * @return array<string, string>
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * Sets a map of tags for this event.
     *
     * @param array<string, string> $tags The tags
     */
    public function setTags(array $tags): void
    {
        $this->tags = array_merge($this->tags, $tags);
    }

    /**
     * Gets the ID of the span.
     */
    public function getSpanId(): SpanId
    {
        return $this->spanId;
    }

    /**
     * Gets the flag determining whether this span should be sampled or not.
     */
    public function getSampled(): ?bool
    {
        return $this->sampled;
    }

    /**
     * Sets the flag determining whether this span should be sampled or not.
     *
     * @param bool $sampled Whether to sample or not this span
     */
    public function setSampled(?bool $sampled): void
    {
        $this->sampled = $sampled;
    }

    /**
     * Gets a map of arbitrary data.
     *
     * @return array<string, mixed>
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Sets a map of arbitrary data. This method will merge the given data with
     * the existing one.
     *
     * @param array<string, mixed> $data The data
     */
    public function setData(array $data): void
    {
        $this->data = array_merge($this->data, $data);
    }

    /**
     * Gets the data in a format suitable for storage in the "trace" context.
     *
     * @return array<string, mixed>
     *
     * @psalm-return array{
     *     data?: array<string, mixed>,
     *     description?: string,
     *     op?: string,
     *     parent_span_id?: string,
     *     span_id: string,
     *     status?: string,
     *     tags?: array<string, string>,
     *     trace_id: string
     * }
     */
    public function getTraceContext(): array
    {
        $result = [
            'span_id' => (string) $this->spanId,
            'trace_id' => (string) $this->traceId,
        ];

        if (null !== $this->parentSpanId) {
            $result['parent_span_id'] = (string) $this->parentSpanId;
        }

        if (null !== $this->description) {
            $result['description'] = $this->description;
        }

        if (null !== $this->op) {
            $result['op'] = $this->op;
        }

        if (null !== $this->status) {
            $result['status'] = (string) $this->status;
        }

        if (!empty($this->data)) {
            $result['data'] = $this->data;
        }

        if (!empty($this->tags)) {
            $result['tags'] = $this->tags;
        }

        return $result;
    }

    /**
     * Sets the finish timestamp on the current span.
     *
     * @param float|null $endTimestamp Takes an endTimestamp if the end should not be the time when you call this function
     *
     * @return EventId|null Finish for a span always returns null
     */
    public function finish(?float $endTimestamp = null): ?EventId
    {
        $this->endTimestamp = $endTimestamp ?? microtime(true);

        return null;
    }

    /**
     * Creates a new {@see Span} while setting the current ID as `parentSpanId`.
     * Also the `sampled` decision will be inherited.
     *
     * @param SpanContext $context The context of the child span
     */
    public function startChild(SpanContext $context): self
    {
        $context = clone $context;
        $context->setSampled($this->sampled);
        $context->setParentSpanId($this->spanId);
        $context->setTraceId($this->traceId);

        $span = new self($context);
        $span->transaction = $this->transaction;
        $span->spanRecorder = $this->spanRecorder;

        if (null != $span->spanRecorder) {
            $span->spanRecorder->add($span);
        }

        return $span;
    }

    /**
     * Gets the span recorder attached to this span.
     *
     * @internal
     */
    public function getSpanRecorder(): ?SpanRecorder
    {
        return $this->spanRecorder;
    }

    /**
     * Detaches the span recorder from this instance.
     */
    public function detachSpanRecorder(): void
    {
        $this->spanRecorder = null;
    }

    /**
     * Returns the transaction containing this span.
     */
    public function getTransaction(): ?Transaction
    {
        return $this->transaction;
    }

    /**
     * Returns a string that can be used for the `sentry-trace` header & meta tag.
     */
    public function toTraceparent(): string
    {
        $sampled = '';

        if (null !== $this->sampled) {
            $sampled = $this->sampled ? '-1' : '-0';
        }

        return sprintf('%s-%s%s', (string) $this->traceId, (string) $this->spanId, $sampled);
    }

    /**
     * Returns a string that can be used for the `baggage` header & meta tag.
     */
    public function toBaggage(): string
    {
        $transaction = $this->getTransaction();

        if (null !== $transaction) {
            return (string) $transaction->getDynamicSamplingContext();
        }

        return '';
    }
}
