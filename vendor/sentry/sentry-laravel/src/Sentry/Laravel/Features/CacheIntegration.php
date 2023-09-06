<?php

namespace Sentry\Laravel\Features;

use Illuminate\Cache\Events;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Redis\Events as RedisEvents;
use Illuminate\Redis\RedisManager;
use Illuminate\Support\Str;
use Sentry\Breadcrumb;
use Sentry\Laravel\Features\Concerns\ResolvesEventOrigin;
use Sentry\Laravel\Integration;
use Sentry\SentrySdk;
use Sentry\Tracing\SpanContext;

class CacheIntegration extends Feature
{
    use ResolvesEventOrigin;

    public function isApplicable(): bool
    {
        return $this->isTracingFeatureEnabled('redis_commands')
            || $this->isBreadcrumbFeatureEnabled('cache');
    }

    public function onBoot(Dispatcher $events): void
    {
        if ($this->isBreadcrumbFeatureEnabled('cache')) {
            $events->listen([
                Events\CacheHit::class,
                Events\CacheMissed::class,
                Events\KeyWritten::class,
                Events\KeyForgotten::class,
            ], [$this, 'handleCacheEvent']);
        }

        if ($this->isTracingFeatureEnabled('redis_commands', false)) {
            $events->listen(RedisEvents\CommandExecuted::class, [$this, 'handleRedisCommand']);

            $this->container()->afterResolving(RedisManager::class, static function (RedisManager $redis): void {
                $redis->enableEvents();
            });
        }
    }

    public function handleCacheEvent(Events\CacheEvent $event): void
    {
        switch (true) {
            case $event instanceof Events\KeyWritten:
                $message = 'Written';
                break;
            case $event instanceof Events\KeyForgotten:
                $message = 'Forgotten';
                break;
            case $event instanceof Events\CacheMissed:
                $message = 'Missed';
                break;
            case $event instanceof Events\CacheHit:
                $message = 'Read';
                break;
            default:
                // In case events are added in the future we do nothing when an unknown event is encountered
                return;
        }

        Integration::addBreadcrumb(new Breadcrumb(
            Breadcrumb::LEVEL_INFO,
            Breadcrumb::TYPE_DEFAULT,
            'cache',
            "{$message}: {$event->key}",
            $event->tags ? ['tags' => $event->tags] : []
        ));
    }

    public function handleRedisCommand(RedisEvents\CommandExecuted $event): void
    {
        $parentSpan = SentrySdk::getCurrentHub()->getSpan();

        // If there is no tracing span active there is no need to handle the event
        if ($parentSpan === null) {
            return;
        }

        $context = new SpanContext();
        $context->setOp('db.redis');

        $keyForDescription = '';

        // If the first parameter is a string and does not contain a newline we use it as the description since it's most likely a key
        // This is not a perfect solution but it's the best we can do without understanding the command that was executed
        if (!empty($event->parameters[0]) && is_string($event->parameters[0]) && !Str::contains($event->parameters[0], "\n")) {
            $keyForDescription = $event->parameters[0];
        }

        $context->setDescription(rtrim(strtoupper($event->command) . ' ' . $keyForDescription));
        $context->setStartTimestamp(microtime(true) - $event->time / 1000);
        $context->setEndTimestamp($context->getStartTimestamp() + $event->time / 1000);

        $data = [
            'db.redis.connection' => $event->connectionName,
        ];

        if ($this->shouldSendDefaultPii()) {
            $data['db.redis.parameters'] = $event->parameters;
        }

        if ($this->isTracingFeatureEnabled('redis_origin')) {
            $commandOrigin = $this->resolveEventOrigin();

            if ($commandOrigin !== null) {
                $data['db.redis.origin'] = $commandOrigin;
            }
        }

        $context->setData($data);

        $parentSpan->startChild($context);
    }
}
