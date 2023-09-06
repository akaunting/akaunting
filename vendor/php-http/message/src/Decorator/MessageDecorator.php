<?php

namespace Http\Message\Decorator;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

/**
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
trait MessageDecorator
{
    /**
     * @var MessageInterface
     */
    private $message;

    /**
     * Returns the decorated message.
     *
     * Since the underlying Message is immutable as well
     * exposing it is not an issue, because it's state cannot be altered
     */
    public function getMessage(): MessageInterface
    {
        return $this->message;
    }

    public function getProtocolVersion(): string
    {
        return $this->message->getProtocolVersion();
    }

    public function withProtocolVersion(string $version): MessageInterface
    {
        $new = clone $this;
        $new->message = $this->message->withProtocolVersion($version);

        return $new;
    }

    public function getHeaders(): array
    {
        return $this->message->getHeaders();
    }

    public function hasHeader(string $header): bool
    {
        return $this->message->hasHeader($header);
    }

    public function getHeader(string $header): array
    {
        return $this->message->getHeader($header);
    }

    public function getHeaderLine(string $header): string
    {
        return $this->message->getHeaderLine($header);
    }

    public function withHeader(string $header, $value): MessageInterface
    {
        $new = clone $this;
        $new->message = $this->message->withHeader($header, $value);

        return $new;
    }

    public function withAddedHeader(string $header, $value): MessageInterface
    {
        $new = clone $this;
        $new->message = $this->message->withAddedHeader($header, $value);

        return $new;
    }

    public function withoutHeader(string $header): MessageInterface
    {
        $new = clone $this;
        $new->message = $this->message->withoutHeader($header);

        return $new;
    }

    public function getBody(): StreamInterface
    {
        return $this->message->getBody();
    }

    public function withBody(StreamInterface $body): MessageInterface
    {
        $new = clone $this;
        $new->message = $this->message->withBody($body);

        return $new;
    }
}
