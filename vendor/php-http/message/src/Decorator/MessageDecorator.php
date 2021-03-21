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
     *
     * @return MessageInterface
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * {@inheritdoc}
     */
    public function getProtocolVersion()
    {
        return $this->message->getProtocolVersion();
    }

    /**
     * {@inheritdoc}
     */
    public function withProtocolVersion($version)
    {
        $new = clone $this;
        $new->message = $this->message->withProtocolVersion($version);

        return $new;
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaders()
    {
        return $this->message->getHeaders();
    }

    /**
     * {@inheritdoc}
     */
    public function hasHeader($header)
    {
        return $this->message->hasHeader($header);
    }

    /**
     * {@inheritdoc}
     */
    public function getHeader($header)
    {
        return $this->message->getHeader($header);
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaderLine($header)
    {
        return $this->message->getHeaderLine($header);
    }

    /**
     * {@inheritdoc}
     */
    public function withHeader($header, $value)
    {
        $new = clone $this;
        $new->message = $this->message->withHeader($header, $value);

        return $new;
    }

    /**
     * {@inheritdoc}
     */
    public function withAddedHeader($header, $value)
    {
        $new = clone $this;
        $new->message = $this->message->withAddedHeader($header, $value);

        return $new;
    }

    /**
     * {@inheritdoc}
     */
    public function withoutHeader($header)
    {
        $new = clone $this;
        $new->message = $this->message->withoutHeader($header);

        return $new;
    }

    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        return $this->message->getBody();
    }

    /**
     * {@inheritdoc}
     */
    public function withBody(StreamInterface $body)
    {
        $new = clone $this;
        $new->message = $this->message->withBody($body);

        return $new;
    }
}
