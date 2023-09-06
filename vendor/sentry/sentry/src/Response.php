<?php

declare(strict_types=1);

namespace Sentry;

/**
 * This class contains the details of the sending operation of an event, e.g.
 * if it was sent successfully or if it was skipped because of some reason.
 */
final class Response
{
    /**
     * @var ResponseStatus The status of the sending operation of the event
     */
    private $status;

    /**
     * @var Event|null The instance of the event being sent, or null if it
     *                 was not available yet
     */
    private $event;

    public function __construct(ResponseStatus $status, ?Event $event = null)
    {
        $this->status = $status;
        $this->event = $event;
    }

    /**
     * Gets the status of the sending operation of the event.
     */
    public function getStatus(): ResponseStatus
    {
        return $this->status;
    }

    /**
     * Gets the instance of the event being sent, or null if it was not available yet.
     */
    public function getEvent(): ?Event
    {
        return $this->event;
    }
}
