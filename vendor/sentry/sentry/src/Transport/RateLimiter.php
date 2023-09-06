<?php

declare(strict_types=1);

namespace Sentry\Transport;

use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Sentry\Event;
use Sentry\EventType;
use Sentry\Response;
use Sentry\ResponseStatus;

final class RateLimiter
{
    /**
     * The name of the header to look at to know the rate limits for the events
     * categories supported by the server.
     */
    private const RATE_LIMITS_HEADER = 'X-Sentry-Rate-Limits';

    /**
     * The name of the header to look at to know after how many seconds the HTTP
     * request should be retried.
     */
    private const RETRY_AFTER_HEADER = 'Retry-After';

    /**
     * The number of seconds after which an HTTP request can be retried.
     */
    private const DEFAULT_RETRY_AFTER_DELAY_SECONDS = 60;

    /**
     * @var LoggerInterface An instance of a PSR-3 compatible logger
     */
    private $logger;

    /**
     * @var array<string, int> The map of time instants for each event category after
     *                         which an HTTP request can be retried
     */
    private $rateLimits = [];

    public function __construct(?LoggerInterface $logger = null)
    {
        $this->logger = $logger ?? new NullLogger();
    }

    public function handleResponse(Event $event, ResponseInterface $response): Response
    {
        $sendResponse = new Response(ResponseStatus::createFromHttpStatusCode($response->getStatusCode()), $event);

        if ($this->handleRateLimit($response)) {
            $eventType = $event->getType();
            $disabledUntil = $this->getDisabledUntil($eventType);

            $this->logger->warning(
                sprintf('Rate limited exceeded for requests of type "%s", backing off until "%s".', (string) $eventType, gmdate(\DATE_ATOM, $disabledUntil)),
                ['event' => $event]
            );
        }

        return $sendResponse;
    }

    public function isRateLimited(EventType $eventType): bool
    {
        $disabledUntil = $this->getDisabledUntil($eventType);

        return $disabledUntil > time();
    }

    private function getDisabledUntil(EventType $eventType): int
    {
        $category = (string) $eventType;

        if ($eventType === EventType::event()) {
            $category = 'error';
        }

        return max($this->rateLimits['all'] ?? 0, $this->rateLimits[$category] ?? 0);
    }

    private function handleRateLimit(ResponseInterface $response): bool
    {
        $now = time();

        if ($response->hasHeader(self::RATE_LIMITS_HEADER)) {
            foreach (explode(',', $response->getHeaderLine(self::RATE_LIMITS_HEADER)) as $limit) {
                $parameters = explode(':', $limit, 3);
                $parameters = array_splice($parameters, 0, 2);
                $delay = ctype_digit($parameters[0]) ? (int) $parameters[0] : self::DEFAULT_RETRY_AFTER_DELAY_SECONDS;

                foreach (explode(';', $parameters[1]) as $category) {
                    $this->rateLimits[$category ?: 'all'] = $now + $delay;
                }
            }

            return true;
        }

        if ($response->hasHeader(self::RETRY_AFTER_HEADER)) {
            $delay = $this->parseRetryAfterHeader($now, $response->getHeaderLine(self::RETRY_AFTER_HEADER));

            $this->rateLimits['all'] = $now + $delay;

            return true;
        }

        return false;
    }

    private function parseRetryAfterHeader(int $currentTime, string $header): int
    {
        if (1 === preg_match('/^\d+$/', $header)) {
            return (int) $header;
        }

        $headerDate = \DateTimeImmutable::createFromFormat(\DateTimeImmutable::RFC1123, $header);

        if (false !== $headerDate && $headerDate->getTimestamp() >= $currentTime) {
            return $headerDate->getTimestamp() - $currentTime;
        }

        return self::DEFAULT_RETRY_AFTER_DELAY_SECONDS;
    }
}
