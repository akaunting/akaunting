<?php

declare(strict_types=1);

namespace Sentry\Transport;

use GuzzleHttp\Promise\PromiseInterface;
use Sentry\Event;

/**
 * This interface must be implemented by all classes willing to provide a way
 * of sending events to a Sentry server.
 *
 * @author Stefano Arlandini <sarlandini@alice.it>
 */
interface TransportInterface
{
    /**
     * Sends the given event.
     *
     * @param Event $event The event
     *
     * @return PromiseInterface Returns the ID of the event or `null` if it failed to be sent
     */
    public function send(Event $event): PromiseInterface;

    /**
     * Waits until all pending requests have been sent or the timeout expires.
     *
     * @param int|null $timeout Maximum time in seconds before the sending
     *                          operation is interrupted
     */
    public function close(?int $timeout = null): PromiseInterface;
}
