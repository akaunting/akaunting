<?php

declare(strict_types=1);

namespace Sentry\Profiling;

use Sentry\Options;

/**
 * @internal
 */
final class Profiler
{
    /**
     * @var \ExcimerProfiler|null
     */
    private $profiler;

    /**
     * @var Profile
     */
    private $profile;

    /**
     * @var float The sample rate (10.01ms/101 Hz)
     */
    private const SAMPLE_RATE = 0.0101;

    /**
     * @var int The maximum stack depth
     */
    private const MAX_STACK_DEPTH = 128;

    public function __construct(?Options $options = null)
    {
        $this->profile = new Profile($options);

        $this->initProfiler();
    }

    public function start(): void
    {
        if (null !== $this->profiler) {
            $this->profiler->start();
        }
    }

    public function stop(): void
    {
        if (null !== $this->profiler) {
            $this->profiler->stop();

            $this->profile->setExcimerLog($this->profiler->flush());
        }
    }

    public function getProfile(): ?Profile
    {
        if (null === $this->profiler) {
            return null;
        }

        return $this->profile;
    }

    private function initProfiler(): void
    {
        if (\extension_loaded('excimer') && \PHP_VERSION_ID >= 70300) {
            $this->profiler = new \ExcimerProfiler();
            $this->profile->setStartTimeStamp(microtime(true));

            $this->profiler->setEventType(EXCIMER_REAL);
            $this->profiler->setPeriod(self::SAMPLE_RATE);
            $this->profiler->setMaxDepth(self::MAX_STACK_DEPTH);
        }
    }
}
