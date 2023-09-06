<?php

declare(strict_types=1);

namespace Sentry\Tracing;

final class TransactionMetadata
{
    /**
     * @var float|int|null
     */
    private $samplingRate;

    /**
     * @var DynamicSamplingContext|null
     */
    private $dynamicSamplingContext;

    /**
     * @var TransactionSource|null
     */
    private $source;

    /**
     * Constructor.
     *
     * @param float|int|null              $samplingRate           The sampling rate
     * @param DynamicSamplingContext|null $dynamicSamplingContext The Dynamic Sampling Context
     * @param TransactionSource|null      $source                 The transaction source
     */
    public function __construct(
        $samplingRate = null,
        ?DynamicSamplingContext $dynamicSamplingContext = null,
        ?TransactionSource $source = null
    ) {
        $this->samplingRate = $samplingRate;
        $this->dynamicSamplingContext = $dynamicSamplingContext;
        $this->source = $source ?? TransactionSource::custom();
    }

    /**
     * @return float|int|null
     */
    public function getSamplingRate()
    {
        return $this->samplingRate;
    }

    /**
     * @param float|int|null $samplingRate
     */
    public function setSamplingRate($samplingRate): void
    {
        $this->samplingRate = $samplingRate;
    }

    public function getDynamicSamplingContext(): ?DynamicSamplingContext
    {
        return $this->dynamicSamplingContext;
    }

    public function setDynamicSamplingContext(?DynamicSamplingContext $dynamicSamplingContext): void
    {
        $this->dynamicSamplingContext = $dynamicSamplingContext;
    }

    public function getSource(): ?TransactionSource
    {
        return $this->source;
    }

    public function setSource(?TransactionSource $source): void
    {
        $this->source = $source;
    }
}
