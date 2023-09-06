<?php

declare(strict_types=1);

namespace Sentry;

use Sentry\Util\SentryUid;

final class CheckIn
{
    /**
     * @var string The check-in ID
     */
    private $id;

    /**
     * @var string The monitor slug
     */
    private $monitorSlug;

    /**
     * @var \Sentry\CheckInStatus The status of the check-in
     */
    private $status;

    /**
     * @var string|null The release
     */
    private $release;

    /**
     * @var string|null The environment
     */
    private $environment;

    /**
     * @var int|float|null The duration of the check-in in seconds
     */
    private $duration;

    /**
     * @var \Sentry\MonitorConfig|null The monitor configuration
     */
    private $monitorConfig;

    /**
     * @param int|float|null $duration The duration of the check-in in seconds
     */
    public function __construct(
        string $monitorSlug,
        CheckInStatus $status,
        string $id = null,
        ?string $release = null,
        ?string $environment = null,
        $duration = null,
        ?MonitorConfig $monitorConfig = null
    ) {
        $this->setMonitorSlug($monitorSlug);
        $this->setStatus($status);

        $this->setId($id ?? SentryUid::generate());
        $this->setRelease($release ?? '');
        $this->setEnvironment($environment ?? Event::DEFAULT_ENVIRONMENT);
        $this->setDuration($duration);
        $this->setMonitorConfig($monitorConfig);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getMonitorSlug(): string
    {
        return $this->monitorSlug;
    }

    public function setMonitorSlug(string $monitorSlug): void
    {
        $this->monitorSlug = $monitorSlug;
    }

    public function getStatus(): CheckInStatus
    {
        return $this->status;
    }

    public function setStatus(CheckInStatus $status): void
    {
        $this->status = $status;
    }

    public function getRelease(): ?string
    {
        return $this->release;
    }

    public function setRelease(string $release): void
    {
        $this->release = $release;
    }

    public function getEnvironment(): ?string
    {
        return $this->environment;
    }

    public function setEnvironment(string $environment): void
    {
        $this->environment = $environment;
    }

    /**
     * @return int|float|null
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param int|float|null $duration The duration of the check-in in seconds
     */
    public function setDuration($duration): void
    {
        $this->duration = $duration;
    }

    public function getMonitorConfig(): ?MonitorConfig
    {
        return $this->monitorConfig;
    }

    public function setMonitorConfig(?MonitorConfig $monitorConfig): void
    {
        $this->monitorConfig = $monitorConfig;
    }
}
