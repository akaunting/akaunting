<?php

declare(strict_types=1);

namespace Sentry\Integration;

use Sentry\Event;
use Sentry\SentrySdk;
use Sentry\State\Scope;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * This integration decides whether an event should not be captured according
 * to a series of options that must match with its data.
 *
 * @deprecated since version 3.17, to be removed in 4.0. Use the `ignore_exceptions` option instead
 *
 * @author Stefano Arlandini <sarlandini@alice.it>
 *
 * @psalm-type IntegrationOptions array{
 *     ignore_exceptions: list<class-string<\Throwable>>,
 *     ignore_tags: array<string, string>
 * }
 */
final class IgnoreErrorsIntegration implements IntegrationInterface
{
    /**
     * @var array<string, mixed> The options
     *
     * @psalm-var IntegrationOptions
     */
    private $options;

    /**
     * Creates a new instance of this integration and configures it with the
     * given options.
     *
     * @param array<string, mixed> $options The options
     *
     * @psalm-param array{
     *     ignore_exceptions?: list<class-string<\Throwable>>,
     *     ignore_tags?: array<string, string>
     * } $options
     */
    public function __construct(array $options = [])
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'ignore_exceptions' => [],
            'ignore_tags' => [],
        ]);

        $resolver->setAllowedTypes('ignore_exceptions', ['array']);
        $resolver->setAllowedTypes('ignore_tags', ['array']);

        $this->options = $resolver->resolve($options);
    }

    /**
     * {@inheritdoc}
     */
    public function setupOnce(): void
    {
        Scope::addGlobalEventProcessor(static function (Event $event): ?Event {
            $integration = SentrySdk::getCurrentHub()->getIntegration(self::class);

            if (null !== $integration && $integration->shouldDropEvent($event, $integration->options)) {
                return null;
            }

            return $event;
        });
    }

    /**
     * Checks whether the given event should be dropped according to the options
     * that configures the current instance of this integration.
     *
     * @param Event                $event   The event to check
     * @param array<string, mixed> $options The options of the integration
     *
     * @psalm-param IntegrationOptions $options
     */
    private function shouldDropEvent(Event $event, array $options): bool
    {
        if ($this->isIgnoredException($event, $options)) {
            return true;
        }

        if ($this->isIgnoredTag($event, $options)) {
            return true;
        }

        return false;
    }

    /**
     * Checks whether the given event should be dropped or not according to the
     * criteria defined in the integration's options.
     *
     * @param Event                $event   The event instance
     * @param array<string, mixed> $options The options of the integration
     *
     * @psalm-param IntegrationOptions $options
     */
    private function isIgnoredException(Event $event, array $options): bool
    {
        $exceptions = $event->getExceptions();

        if (empty($exceptions)) {
            return false;
        }

        foreach ($options['ignore_exceptions'] as $ignoredException) {
            if (is_a($exceptions[0]->getType(), $ignoredException, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks whether the given event should be dropped or not according to the
     * criteria defined in the integration's options.
     *
     * @param Event                $event   The event instance
     * @param array<string, mixed> $options The options of the integration
     *
     * @psalm-param IntegrationOptions $options
     */
    private function isIgnoredTag(Event $event, array $options): bool
    {
        $tags = $event->getTags();

        if (empty($tags)) {
            return false;
        }

        foreach ($options['ignore_tags'] as $key => $value) {
            if (isset($tags[$key]) && $tags[$key] === $value) {
                return true;
            }
        }

        return false;
    }
}
