<?php

namespace Http\Message\Decorator;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

/**
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
trait RequestDecorator
{
    use MessageDecorator {
        getMessage as getRequest;
    }

    /**
     * Exchanges the underlying request with another.
     *
     * @return self
     */
    public function withRequest(RequestInterface $request)
    {
        $new = clone $this;
        $new->message = $request;

        return $new;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestTarget()
    {
        return $this->message->getRequestTarget();
    }

    /**
     * {@inheritdoc}
     */
    public function withRequestTarget($requestTarget)
    {
        $new = clone $this;
        $new->message = $this->message->withRequestTarget($requestTarget);

        return $new;
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod()
    {
        return $this->message->getMethod();
    }

    /**
     * {@inheritdoc}
     */
    public function withMethod($method)
    {
        $new = clone $this;
        $new->message = $this->message->withMethod($method);

        return $new;
    }

    /**
     * {@inheritdoc}
     */
    public function getUri()
    {
        return $this->message->getUri();
    }

    /**
     * {@inheritdoc}
     */
    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        $new = clone $this;
        $new->message = $this->message->withUri($uri, $preserveHost);

        return $new;
    }
}
