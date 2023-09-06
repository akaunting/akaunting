<?php

declare(strict_types=1);

namespace Http\Client\Common\HttpClientPool;

use Http\Client\Common\FlexibleHttpClient;
use Http\Client\Exception;
use Http\Client\HttpAsyncClient;
use Http\Client\HttpClient;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * A HttpClientPoolItem represent a HttpClient inside a Pool.
 *
 * It is disabled when a request failed and can be reenabled after a certain number of seconds.
 * It also keep tracks of the current number of open requests the client is currently being sending
 * (only usable for async method).
 *
 * This class is used internally in the client pools and is not supposed to be used anywhere else.
 *
 * @final
 *
 * @internal
 *
 * @author Joel Wurtz <joel.wurtz@gmail.com>
 */
class HttpClientPoolItem implements HttpClient, HttpAsyncClient
{
    /**
     * @var int Number of request this client is currently sending
     */
    private $sendingRequestCount = 0;

    /**
     * @var \DateTime|null Time when this client has been disabled or null if enable
     */
    private $disabledAt;

    /**
     * Number of seconds until this client is enabled again after an error.
     *
     * null: never reenable this client.
     *
     * @var int|null
     */
    private $reenableAfter;

    /**
     * @var FlexibleHttpClient A http client responding to async and sync request
     */
    private $client;

    /**
     * @param ClientInterface|HttpAsyncClient $client
     * @param int|null                        $reenableAfter Number of seconds until this client is enabled again after an error
     */
    public function __construct($client, int $reenableAfter = null)
    {
        if (!$client instanceof ClientInterface && !$client instanceof HttpAsyncClient) {
            throw new \TypeError(
                sprintf('%s::__construct(): Argument #1 ($client) must be of type %s|%s, %s given', self::class, ClientInterface::class, HttpAsyncClient::class, get_debug_type($client))
            );
        }

        $this->client = new FlexibleHttpClient($client);
        $this->reenableAfter = $reenableAfter;
    }

    /**
     * {@inheritdoc}
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        if ($this->isDisabled()) {
            throw new Exception\RequestException('Cannot send the request as this client has been disabled', $request);
        }

        try {
            $this->incrementRequestCount();
            $response = $this->client->sendRequest($request);
            $this->decrementRequestCount();
        } catch (Exception $e) {
            $this->disable();
            $this->decrementRequestCount();

            throw $e;
        }

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function sendAsyncRequest(RequestInterface $request)
    {
        if ($this->isDisabled()) {
            throw new Exception\RequestException('Cannot send the request as this client has been disabled', $request);
        }

        $this->incrementRequestCount();

        return $this->client->sendAsyncRequest($request)->then(function ($response) {
            $this->decrementRequestCount();

            return $response;
        }, function ($exception) {
            $this->disable();
            $this->decrementRequestCount();

            throw $exception;
        });
    }

    /**
     * Whether this client is disabled or not.
     *
     * If the client was disabled, calling this method checks if the client can
     * be reenabled and if so enables it.
     */
    public function isDisabled(): bool
    {
        if (null !== $this->reenableAfter && null !== $this->disabledAt) {
            // Reenable after a certain time
            $now = new \DateTime();

            if (($now->getTimestamp() - $this->disabledAt->getTimestamp()) >= $this->reenableAfter) {
                $this->enable();

                return false;
            }

            return true;
        }

        return null !== $this->disabledAt;
    }

    /**
     * Get current number of request that are currently being sent by the underlying HTTP client.
     */
    public function getSendingRequestCount(): int
    {
        return $this->sendingRequestCount;
    }

    /**
     * Increment the request count.
     */
    private function incrementRequestCount(): void
    {
        ++$this->sendingRequestCount;
    }

    /**
     * Decrement the request count.
     */
    private function decrementRequestCount(): void
    {
        --$this->sendingRequestCount;
    }

    /**
     * Enable the current client.
     */
    private function enable(): void
    {
        $this->disabledAt = null;
    }

    /**
     * Disable the current client.
     */
    private function disable(): void
    {
        $this->disabledAt = new \DateTime('now');
    }
}
