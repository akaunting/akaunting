<?php

declare(strict_types=1);

namespace Http\Client\Common;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Responses and exceptions returned from parallel request execution.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
final class BatchResult
{
    /**
     * @var \SplObjectStorage<RequestInterface, ResponseInterface>
     */
    private $responses;

    /**
     * @var \SplObjectStorage<RequestInterface, ClientExceptionInterface>
     */
    private $exceptions;

    public function __construct()
    {
        $this->responses = new \SplObjectStorage();
        $this->exceptions = new \SplObjectStorage();
    }

    /**
     * Checks if there are any successful responses at all.
     */
    public function hasResponses(): bool
    {
        return $this->responses->count() > 0;
    }

    /**
     * Returns all successful responses.
     *
     * @return ResponseInterface[]
     */
    public function getResponses(): array
    {
        $responses = [];

        foreach ($this->responses as $request) {
            $responses[] = $this->responses[$request];
        }

        return $responses;
    }

    /**
     * Checks if there is a successful response for a request.
     */
    public function isSuccessful(RequestInterface $request): bool
    {
        return $this->responses->contains($request);
    }

    /**
     * Returns the response for a successful request.
     *
     * @throws \UnexpectedValueException If request was not part of the batch or failed
     */
    public function getResponseFor(RequestInterface $request): ResponseInterface
    {
        try {
            return $this->responses[$request];
        } catch (\UnexpectedValueException $e) {
            throw new \UnexpectedValueException('Request not found', $e->getCode(), $e);
        }
    }

    /**
     * Adds a response in an immutable way.
     *
     * @return BatchResult the new BatchResult with this request-response pair added to it
     */
    public function addResponse(RequestInterface $request, ResponseInterface $response): self
    {
        $new = clone $this;
        $new->responses->attach($request, $response);

        return $new;
    }

    /**
     * Checks if there are any unsuccessful requests at all.
     */
    public function hasExceptions(): bool
    {
        return $this->exceptions->count() > 0;
    }

    /**
     * Returns all exceptions for the unsuccessful requests.
     *
     * @return ClientExceptionInterface[]
     */
    public function getExceptions(): array
    {
        $exceptions = [];

        foreach ($this->exceptions as $request) {
            $exceptions[] = $this->exceptions[$request];
        }

        return $exceptions;
    }

    /**
     * Checks if there is an exception for a request, meaning the request failed.
     */
    public function isFailed(RequestInterface $request): bool
    {
        return $this->exceptions->contains($request);
    }

    /**
     * Returns the exception for a failed request.
     *
     * @throws \UnexpectedValueException If request was not part of the batch or was successful
     */
    public function getExceptionFor(RequestInterface $request): ClientExceptionInterface
    {
        try {
            return $this->exceptions[$request];
        } catch (\UnexpectedValueException $e) {
            throw new \UnexpectedValueException('Request not found', $e->getCode(), $e);
        }
    }

    /**
     * Adds an exception in an immutable way.
     *
     * @return BatchResult the new BatchResult with this request-exception pair added to it
     */
    public function addException(RequestInterface $request, ClientExceptionInterface $exception): self
    {
        $new = clone $this;
        $new->exceptions->attach($request, $exception);

        return $new;
    }

    public function __clone()
    {
        $this->responses = clone $this->responses;
        $this->exceptions = clone $this->exceptions;
    }
}
