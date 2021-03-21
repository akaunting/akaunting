<?php

declare(strict_types=1);

namespace NunoMaduro\Larastan\Concerns;

use PHPStan\Broker\Broker;

/**
 * @internal
 */
trait HasBroker
{
    /**
     * @var \PHPStan\Broker\Broker
     */
    protected $broker;

    /**
     * @param \PHPStan\Broker\Broker $broker
     */
    public function setBroker(Broker $broker): void
    {
        $this->broker = $broker;
    }

    /**
     * Returns the current broker.
     *
     * @return \PHPStan\Broker\Broker
     */
    public function getBroker(): Broker
    {
        return $this->broker;
    }
}
