<?php

declare(strict_types=1);

namespace Http\Client\Common;

use Http\Promise\Promise;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * A deferred allow to return a promise which has not been resolved yet.
 */
final class Deferred implements Promise
{
    /**
     * @var ResponseInterface|null
     */
    private $value;

    /**
     * @var ClientExceptionInterface|null
     */
    private $failure;

    /**
     * @var string
     */
    private $state;

    /**
     * @var callable
     */
    private $waitCallback;

    /**
     * @var callable[]
     */
    private $onFulfilledCallbacks;

    /**
     * @var callable[]
     */
    private $onRejectedCallbacks;

    public function __construct(callable $waitCallback)
    {
        $this->waitCallback = $waitCallback;
        $this->state = Promise::PENDING;
        $this->onFulfilledCallbacks = [];
        $this->onRejectedCallbacks = [];
    }

    /**
     * {@inheritdoc}
     */
    public function then(callable $onFulfilled = null, callable $onRejected = null): Promise
    {
        $deferred = new self($this->waitCallback);

        $this->onFulfilledCallbacks[] = function (ResponseInterface $response) use ($onFulfilled, $deferred) {
            try {
                if (null !== $onFulfilled) {
                    $response = $onFulfilled($response);
                }
                $deferred->resolve($response);
            } catch (ClientExceptionInterface $exception) {
                $deferred->reject($exception);
            }
        };

        $this->onRejectedCallbacks[] = function (ClientExceptionInterface $exception) use ($onRejected, $deferred) {
            try {
                if (null !== $onRejected) {
                    $response = $onRejected($exception);
                    $deferred->resolve($response);

                    return;
                }
                $deferred->reject($exception);
            } catch (ClientExceptionInterface $newException) {
                $deferred->reject($newException);
            }
        };

        return $deferred;
    }

    /**
     * {@inheritdoc}
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * Resolve this deferred with a Response.
     */
    public function resolve(ResponseInterface $response): void
    {
        if (Promise::PENDING !== $this->state) {
            return;
        }

        $this->value = $response;
        $this->state = Promise::FULFILLED;

        foreach ($this->onFulfilledCallbacks as $onFulfilledCallback) {
            $onFulfilledCallback($response);
        }
    }

    /**
     * Reject this deferred with an Exception.
     */
    public function reject(ClientExceptionInterface $exception): void
    {
        if (Promise::PENDING !== $this->state) {
            return;
        }

        $this->failure = $exception;
        $this->state = Promise::REJECTED;

        foreach ($this->onRejectedCallbacks as $onRejectedCallback) {
            $onRejectedCallback($exception);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function wait($unwrap = true)
    {
        if (Promise::PENDING === $this->state) {
            $callback = $this->waitCallback;
            $callback();
        }

        if (!$unwrap) {
            return null;
        }

        if (Promise::FULFILLED === $this->state) {
            return $this->value;
        }

        if (null === $this->failure) {
            throw new \RuntimeException('Internal Error: Promise is not fulfilled but has no exception stored');
        }

        throw $this->failure;
    }
}
