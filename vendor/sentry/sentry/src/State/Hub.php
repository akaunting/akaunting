<?php

declare(strict_types=1);

namespace Sentry\State;

use Sentry\Breadcrumb;
use Sentry\CheckIn;
use Sentry\CheckInStatus;
use Sentry\ClientInterface;
use Sentry\Event;
use Sentry\EventHint;
use Sentry\EventId;
use Sentry\Integration\IntegrationInterface;
use Sentry\MonitorConfig;
use Sentry\Severity;
use Sentry\Tracing\SamplingContext;
use Sentry\Tracing\Span;
use Sentry\Tracing\Transaction;
use Sentry\Tracing\TransactionContext;

/**
 * This class is a basic implementation of the {@see HubInterface} interface.
 */
final class Hub implements HubInterface
{
    /**
     * @var Layer[] The stack of client/scope pairs
     */
    private $stack = [];

    /**
     * @var EventId|null The ID of the last captured event
     */
    private $lastEventId;

    /**
     * Hub constructor.
     *
     * @param ClientInterface|null $client The client bound to the hub
     * @param Scope|null           $scope  The scope bound to the hub
     */
    public function __construct(?ClientInterface $client = null, ?Scope $scope = null)
    {
        $this->stack[] = new Layer($client, $scope ?? new Scope());
    }

    /**
     * {@inheritdoc}
     */
    public function getClient(): ?ClientInterface
    {
        return $this->getStackTop()->getClient();
    }

    /**
     * {@inheritdoc}
     */
    public function getLastEventId(): ?EventId
    {
        return $this->lastEventId;
    }

    /**
     * {@inheritdoc}
     */
    public function pushScope(): Scope
    {
        $clonedScope = clone $this->getScope();

        $this->stack[] = new Layer($this->getClient(), $clonedScope);

        return $clonedScope;
    }

    /**
     * {@inheritdoc}
     */
    public function popScope(): bool
    {
        if (1 === \count($this->stack)) {
            return false;
        }

        return null !== array_pop($this->stack);
    }

    /**
     * {@inheritdoc}
     */
    public function withScope(callable $callback)
    {
        $scope = $this->pushScope();

        try {
            return $callback($scope);
        } finally {
            $this->popScope();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureScope(callable $callback): void
    {
        $callback($this->getScope());
    }

    /**
     * {@inheritdoc}
     */
    public function bindClient(ClientInterface $client): void
    {
        $layer = $this->getStackTop();
        $layer->setClient($client);
    }

    /**
     * {@inheritdoc}
     */
    public function captureMessage(string $message, ?Severity $level = null, ?EventHint $hint = null): ?EventId
    {
        $client = $this->getClient();

        if (null !== $client) {
            return $this->lastEventId = $client->captureMessage($message, $level, $this->getScope(), $hint);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function captureException(\Throwable $exception, ?EventHint $hint = null): ?EventId
    {
        $client = $this->getClient();

        if (null !== $client) {
            return $this->lastEventId = $client->captureException($exception, $this->getScope(), $hint);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function captureEvent(Event $event, ?EventHint $hint = null): ?EventId
    {
        $client = $this->getClient();

        if (null !== $client) {
            return $this->lastEventId = $client->captureEvent($event, $hint, $this->getScope());
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function captureLastError(?EventHint $hint = null): ?EventId
    {
        $client = $this->getClient();

        if (null !== $client) {
            return $this->lastEventId = $client->captureLastError($this->getScope(), $hint);
        }

        return null;
    }

    /**
     * @param string             $slug          Identifier of the Monitor
     * @param CheckInStatus      $status        The status of the check-in
     * @param int|float|null     $duration      The duration of the check-in
     * @param MonitorConfig|null $monitorConfig Configuration of the Monitor
     * @param string|null        $checkInId     A check-in ID from the previous check-in
     */
    public function captureCheckIn(string $slug, CheckInStatus $status, $duration = null, ?MonitorConfig $monitorConfig = null, ?string $checkInId = null): ?string
    {
        $client = $this->getClient();

        if (null === $client) {
            return null;
        }

        $options = $client->getOptions();
        $event = Event::createCheckIn();
        $checkIn = new CheckIn(
            $slug,
            $status,
            $checkInId,
            $options->getRelease(),
            $options->getEnvironment(),
            $duration,
            $monitorConfig
        );
        $event->setCheckIn($checkIn);
        $this->captureEvent($event);

        return $checkIn->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function addBreadcrumb(Breadcrumb $breadcrumb): bool
    {
        $client = $this->getClient();

        if (null === $client) {
            return false;
        }

        $options = $client->getOptions();
        $beforeBreadcrumbCallback = $options->getBeforeBreadcrumbCallback();
        $maxBreadcrumbs = $options->getMaxBreadcrumbs();

        if ($maxBreadcrumbs <= 0) {
            return false;
        }

        $breadcrumb = $beforeBreadcrumbCallback($breadcrumb);

        if (null !== $breadcrumb) {
            $this->getScope()->addBreadcrumb($breadcrumb, $maxBreadcrumbs);
        }

        return null !== $breadcrumb;
    }

    /**
     * {@inheritdoc}
     */
    public function getIntegration(string $className): ?IntegrationInterface
    {
        $client = $this->getClient();

        if (null !== $client) {
            return $client->getIntegration($className);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     *
     * @param array<string, mixed> $customSamplingContext Additional context that will be passed to the {@see SamplingContext}
     */
    public function startTransaction(TransactionContext $context, array $customSamplingContext = []): Transaction
    {
        $transaction = new Transaction($context, $this);
        $client = $this->getClient();
        $options = null !== $client ? $client->getOptions() : null;

        if (null === $options || !$options->isTracingEnabled()) {
            $transaction->setSampled(false);

            return $transaction;
        }

        $samplingContext = SamplingContext::getDefault($context);
        $samplingContext->setAdditionalContext($customSamplingContext);

        $tracesSampler = $options->getTracesSampler();

        if (null === $transaction->getSampled()) {
            if (null !== $tracesSampler) {
                $sampleRate = $tracesSampler($samplingContext);
            } else {
                $sampleRate = $this->getSampleRate(
                    $samplingContext->getParentSampled(),
                    $options->getTracesSampleRate() ?? 0
                );
            }

            if (!$this->isValidSampleRate($sampleRate)) {
                $transaction->setSampled(false);

                return $transaction;
            }

            $transaction->getMetadata()->setSamplingRate($sampleRate);

            if (0.0 === $sampleRate) {
                $transaction->setSampled(false);

                return $transaction;
            }

            $transaction->setSampled($this->sample($sampleRate));
        }

        if (!$transaction->getSampled()) {
            return $transaction;
        }

        $transaction->initSpanRecorder();

        $profilesSampleRate = $options->getProfilesSampleRate();
        if ($this->sample($profilesSampleRate)) {
            $transaction->initProfiler();
            $profiler = $transaction->getProfiler();
            if (null !== $profiler) {
                $profiler->start();
            }
        }

        return $transaction;
    }

    /**
     * {@inheritdoc}
     */
    public function getTransaction(): ?Transaction
    {
        return $this->getScope()->getTransaction();
    }

    /**
     * {@inheritdoc}
     */
    public function setSpan(?Span $span): HubInterface
    {
        $this->getScope()->setSpan($span);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSpan(): ?Span
    {
        return $this->getScope()->getSpan();
    }

    /**
     * Gets the scope bound to the top of the stack.
     */
    private function getScope(): Scope
    {
        return $this->getStackTop()->getScope();
    }

    /**
     * Gets the topmost client/layer pair in the stack.
     */
    private function getStackTop(): Layer
    {
        return $this->stack[\count($this->stack) - 1];
    }

    private function getSampleRate(?bool $hasParentBeenSampled, float $fallbackSampleRate): float
    {
        if (true === $hasParentBeenSampled) {
            return 1;
        }

        if (false === $hasParentBeenSampled) {
            return 0;
        }

        return $fallbackSampleRate;
    }

    /**
     * @param mixed $sampleRate
     */
    private function sample($sampleRate): bool
    {
        if (0.0 === $sampleRate) {
            return false;
        }

        if (1.0 === $sampleRate) {
            return true;
        }

        return mt_rand(0, mt_getrandmax() - 1) / mt_getrandmax() < $sampleRate;
    }

    /**
     * @param mixed $sampleRate
     */
    private function isValidSampleRate($sampleRate): bool
    {
        if (!\is_float($sampleRate) && !\is_int($sampleRate)) {
            return false;
        }

        if ($sampleRate < 0 || $sampleRate > 1) {
            return false;
        }

        return true;
    }
}
