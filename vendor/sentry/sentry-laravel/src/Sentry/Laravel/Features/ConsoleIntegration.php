<?php

namespace Sentry\Laravel\Features;

use Illuminate\Console\Application as ConsoleApplication;
use Illuminate\Console\Scheduling\Event as SchedulingEvent;
use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Str;
use RuntimeException;
use Sentry\CheckIn;
use Sentry\CheckInStatus;
use Sentry\Event as SentryEvent;
use Sentry\MonitorConfig;
use Sentry\MonitorSchedule;
use Sentry\SentrySdk;

class ConsoleIntegration extends Feature
{
    /**
     * @var array<string, CheckIn> The list of checkins that are currently in progress.
     */
    private $checkInStore = [];

    /**
     * @var Cache The cache repository.
     */
    private $cache;

    public function isApplicable(): bool
    {
        return $this->container()->make(Application::class)->runningInConsole();
    }

    public function onBoot(Cache $cache): void
    {
        $this->cache = $cache;

        $startCheckIn = function (?string $slug, SchedulingEvent $scheduled, ?int $checkInMargin, ?int $maxRuntime, bool $updateMonitorConfig) {
            $this->startCheckIn($slug, $scheduled, $checkInMargin, $maxRuntime, $updateMonitorConfig);
        };
        $finishCheckIn = function (?string $slug, SchedulingEvent $scheduled, CheckInStatus $status) {
            $this->finishCheckIn($slug, $scheduled, $status);
        };

        SchedulingEvent::macro('sentryMonitor', function (
            ?string $monitorSlug = null,
            ?int $checkInMargin = null,
            ?int $maxRuntime = null,
            bool $updateMonitorConfig = true
        ) use ($startCheckIn, $finishCheckIn) {
            /** @var SchedulingEvent $this */
            if ($monitorSlug === null && $this->command === null) {
                throw new RuntimeException('The command string is null, please set a slug manually for this scheduled command using the `sentryMonitor(\'your-monitor-slug\')` macro.');
            }

            return $this
                ->before(function () use ($startCheckIn, $monitorSlug, $checkInMargin, $maxRuntime, $updateMonitorConfig) {
                    /** @var SchedulingEvent $this */
                    $startCheckIn($monitorSlug, $this, $checkInMargin, $maxRuntime, $updateMonitorConfig);
                })
                ->onSuccess(function () use ($finishCheckIn, $monitorSlug) {
                    /** @var SchedulingEvent $this */
                    $finishCheckIn($monitorSlug, $this, CheckInStatus::ok());
                })
                ->onFailure(function () use ($finishCheckIn, $monitorSlug) {
                    /** @var SchedulingEvent $this */
                    $finishCheckIn($monitorSlug, $this, CheckInStatus::error());
                });
        });
    }

    public function onBootInactive(): void
    {
        // This is an exact copy of the macro above, but without doing anything so that even when no DSN is configured the user can still use the macro
        SchedulingEvent::macro('sentryMonitor', function (
            ?string $monitorSlug = null,
            ?int $checkInMargin = null,
            ?int $maxRuntime = null,
            bool $updateMonitorConfig = true
        ) {
            return $this;
        });
    }

    private function startCheckIn(?string $slug, SchedulingEvent $scheduled, ?int $checkInMargin, ?int $maxRuntime, bool $updateMonitorConfig): void
    {
        $checkInSlug = $slug ?? $this->makeSlugForScheduled($scheduled);

        $checkIn = $this->createCheckIn($checkInSlug, CheckInStatus::inProgress());

        if ($updateMonitorConfig || $slug === null) {
            $checkIn->setMonitorConfig(new MonitorConfig(
                MonitorSchedule::crontab($scheduled->getExpression()),
                $checkInMargin,
                $maxRuntime,
                $scheduled->timezone
            ));
        }

        $cacheKey = $this->buildCacheKey($scheduled->mutexName(), $checkInSlug);

        $this->checkInStore[$cacheKey] = $checkIn;

        if ($scheduled->runInBackground) {
            $this->cache->store()->put($cacheKey, $checkIn->getId(), $scheduled->expiresAt * 60);
        }

        $this->sendCheckIn($checkIn);
    }

    private function finishCheckIn(?string $slug, SchedulingEvent $scheduled, CheckInStatus $status): void
    {
        $mutex = $scheduled->mutexName();

        $checkInSlug = $slug ?? $this->makeSlugForScheduled($scheduled);

        $cacheKey = $this->buildCacheKey($mutex, $checkInSlug);

        $checkIn = $this->checkInStore[$cacheKey] ?? null;

        if ($checkIn === null && $scheduled->runInBackground) {
            $checkInId = $this->cache->store()->get($cacheKey);

            if ($checkInId !== null) {
                $checkIn = $this->createCheckIn($checkInSlug, $status, $checkInId);
            }
        }

        // This should never happen (because we should always start before we finish), but better safe than sorry
        if ($checkIn === null) {
            return;
        }

        // We don't need to keep the checkIn ID stored since we finished executing the command
        unset($this->checkInStore[$mutex]);

        if ($scheduled->runInBackground) {
            $this->cache->store()->forget($cacheKey);
        }

        $checkIn->setStatus($status);

        $this->sendCheckIn($checkIn);
    }

    private function sendCheckIn(CheckIn $checkIn): void
    {
        $event = SentryEvent::createCheckIn();
        $event->setCheckIn($checkIn);

        SentrySdk::getCurrentHub()->captureEvent($event);
    }

    private function createCheckIn(string $slug, CheckInStatus $status, string $id = null): CheckIn
    {
        $options = SentrySdk::getCurrentHub()->getClient()->getOptions();

        return new CheckIn(
            $slug,
            $status,
            $id,
            $options->getRelease(),
            $options->getEnvironment()
        );
    }

    private function buildCacheKey(string $mutex, string $slug): string
    {
        // We use the mutex name as part of the cache key to avoid collisions between the same commands with the same schedule but with different slugs
        return 'sentry:checkIn:' . sha1("{$mutex}:{$slug}");
    }

    private function makeSlugForScheduled(SchedulingEvent $scheduled): string
    {
        $generatedSlug = Str::slug(
            str_replace(
                // `:` is commonly used in the command name, so we replace it with `-` to avoid it being stripped out by the slug function
                ':',
                '-',
                trim(
                    // The command string always starts with the PHP binary, so we remove it since it's not relevant to the slug
                    Str::after($scheduled->command, ConsoleApplication::phpBinary())
                )
            )
        );

        return "scheduled_{$generatedSlug}";
    }
}
