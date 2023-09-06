<?php

declare(strict_types=1);

namespace Sentry\Tracing;

use Sentry\Options;
use Sentry\State\HubInterface;
use Sentry\State\Scope;

/**
 * This class represents the Dynamic Sampling Context (dsc).
 *
 * @see https://develop.sentry.dev/sdk/performance/dynamic-sampling-context/
 */
final class DynamicSamplingContext
{
    private const SENTRY_ENTRY_PREFIX = 'sentry-';

    /**
     * @var array<string, string> The dsc entries
     */
    private $entries = [];

    /**
     * @var bool Indicates if the dsc is mutable or immutable
     */
    private $isFrozen = false;

    /**
     * Construct a new dsc object.
     */
    private function __construct()
    {
    }

    /**
     * Set a new key value pair on the dsc.
     *
     * @param string $key   the list member key
     * @param string $value the list member value
     */
    public function set(string $key, string $value): void
    {
        if ($this->isFrozen) {
            return;
        }

        $this->entries[$key] = $value;
    }

    /**
     * Check if a key value pair is set on the dsc.
     *
     * @param string $key the list member key
     */
    public function has(string $key): bool
    {
        return isset($this->entries[$key]);
    }

    /**
     * Get a value from the dsc.
     *
     * @param string      $key     the list member key
     * @param string|null $default the default value to return if no value exists
     */
    public function get(string $key, ?string $default = null): ?string
    {
        return $this->entries[$key] ?? $default;
    }

    /**
     * Mark the dsc as frozen.
     */
    public function freeze(): void
    {
        $this->isFrozen = true;
    }

    /**
     * Indicates that the dsc is frozen and cannot be mutated.
     */
    public function isFrozen(): bool
    {
        return $this->isFrozen;
    }

    /**
     * Check if there are any entries set.
     */
    public function hasEntries(): bool
    {
        return !empty($this->entries);
    }

    /**
     * Gets the dsc entries.
     *
     * @return array<string, string>
     */
    public function getEntries(): array
    {
        return $this->entries;
    }

    /**
     * Parse the baggage header.
     *
     * @param string $header the baggage header contents
     */
    public static function fromHeader(string $header): self
    {
        $samplingContext = new self();

        foreach (explode(',', $header) as $listMember) {
            if (empty(trim($listMember))) {
                continue;
            }

            $keyValueAndProperties = explode(';', $listMember, 2);
            $keyValue = trim($keyValueAndProperties[0]);

            if (!str_contains($keyValue, '=')) {
                continue;
            }

            [$key, $value] = explode('=', $keyValue, 2);

            if (str_starts_with($key, self::SENTRY_ENTRY_PREFIX)) {
                $samplingContext->set(rawurldecode(mb_substr($key, mb_strlen(self::SENTRY_ENTRY_PREFIX))), rawurldecode($value));
            }
        }

        // Once we receive a baggage header with Sentry entries from an upstream SDK,
        // we freeze the contents so it cannot be mutated anymore by this SDK.
        // It should only be propagated to the next downstream SDK or the Sentry server itself.
        $samplingContext->isFrozen = $samplingContext->hasEntries();

        return $samplingContext;
    }

    /**
     * Create a dsc object.
     *
     * @see https://develop.sentry.dev/sdk/performance/dynamic-sampling-context/#baggage-header
     */
    public static function fromTransaction(Transaction $transaction, HubInterface $hub): self
    {
        $samplingContext = new self();
        $samplingContext->set('trace_id', (string) $transaction->getTraceId());

        $sampleRate = $transaction->getMetaData()->getSamplingRate();
        if (null !== $sampleRate) {
            $samplingContext->set('sample_rate', (string) $sampleRate);
        }

        // Only include the transaction name if it has good quality
        if ($transaction->getMetadata()->getSource() !== TransactionSource::url()) {
            $samplingContext->set('transaction', $transaction->getName());
        }

        $client = $hub->getClient();

        if (null !== $client) {
            $options = $client->getOptions();

            if (null !== $options->getDsn() && null !== $options->getDsn()->getPublicKey()) {
                $samplingContext->set('public_key', $options->getDsn()->getPublicKey());
            }

            if (null !== $options->getRelease()) {
                $samplingContext->set('release', $options->getRelease());
            }

            if (null !== $options->getEnvironment()) {
                $samplingContext->set('environment', $options->getEnvironment());
            }
        }

        $hub->configureScope(static function (Scope $scope) use ($samplingContext): void {
            if (null !== $scope->getUser() && null !== $scope->getUser()->getSegment()) {
                $samplingContext->set('user_segment', $scope->getUser()->getSegment());
            }
        });

        if (null !== $transaction->getSampled()) {
            $samplingContext->set('sampled', $transaction->getSampled() ? 'true' : 'false');
        }

        $samplingContext->freeze();

        return $samplingContext;
    }

    public static function fromOptions(Options $options, Scope $scope): self
    {
        $samplingContext = new self();
        $samplingContext->set('trace_id', (string) $scope->getPropagationContext()->getTraceId());

        if (null !== $options->getTracesSampleRate()) {
            $samplingContext->set('sample_rate', (string) $options->getTracesSampleRate());
        }

        if (null !== $options->getDsn() && null !== $options->getDsn()->getPublicKey()) {
            $samplingContext->set('public_key', $options->getDsn()->getPublicKey());
        }

        if (null !== $options->getRelease()) {
            $samplingContext->set('release', $options->getRelease());
        }

        if (null !== $options->getEnvironment()) {
            $samplingContext->set('environment', $options->getEnvironment());
        }

        if (null !== $scope->getUser() && null !== $scope->getUser()->getSegment()) {
            $samplingContext->set('user_segment', $scope->getUser()->getSegment());
        }

        $samplingContext->freeze();

        return $samplingContext;
    }

    /**
     * Serialize the dsc as a string.
     */
    public function __toString(): string
    {
        $result = [];

        foreach ($this->entries as $key => $value) {
            $result[] = rawurlencode(self::SENTRY_ENTRY_PREFIX . $key) . '=' . rawurlencode($value);
        }

        return implode(',', $result);
    }
}
