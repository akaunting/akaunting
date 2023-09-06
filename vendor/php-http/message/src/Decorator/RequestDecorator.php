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
     */
    public function withRequest(RequestInterface $request): RequestInterface
    {
        $new = clone $this;
        $new->message = $request;

        return $new;
    }

    public function getRequestTarget(): string
    {
        return $this->message->getRequestTarget();
    }

    public function withRequestTarget(string $requestTarget): RequestInterface
    {
        $new = clone $this;
        $new->message = $this->message->withRequestTarget($requestTarget);

        return $new;
    }

    public function getMethod(): string
    {
        return $this->message->getMethod();
    }

    public function withMethod(string $method): RequestInterface
    {
        $new = clone $this;
        $new->message = $this->message->withMethod($method);

        return $new;
    }

    public function getUri(): UriInterface
    {
        return $this->message->getUri();
    }

    public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface
    {
        $new = clone $this;
        $new->message = $this->message->withUri($uri, $preserveHost);

        return $new;
    }
}
