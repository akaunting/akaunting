<?php

declare(strict_types=1);

namespace Http\Client\Common\HttpClientPool;

use Http\Client\Common\Exception\HttpClientNotFoundException;
use Http\Client\Common\HttpClientPool as HttpClientPoolInterface;
use Http\Client\HttpAsyncClient;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * A http client pool allows to send requests on a pool of different http client using a specific strategy (least used,
 * round robin, ...).
 */
abstract class HttpClientPool implements HttpClientPoolInterface
{
    /**
     * @var HttpClientPoolItem[]
     */
    protected $clientPool = [];

    /**
     * Add a client to the pool.
     *
     * @param ClientInterface|HttpAsyncClient $client
     */
    public function addHttpClient($client): void
    {
        // no need to check for HttpClientPoolItem here, since it extends the other interfaces
        if (!$client instanceof ClientInterface && !$client instanceof HttpAsyncClient) {
            throw new \TypeError(
                sprintf('%s::addHttpClient(): Argument #1 ($client) must be of type %s|%s, %s given', self::class, ClientInterface::class, HttpAsyncClient::class, get_debug_type($client))
            );
        }

        if (!$client instanceof HttpClientPoolItem) {
            $client = new HttpClientPoolItem($client);
        }

        $this->clientPool[] = $client;
    }

    /**
     * Return an http client given a specific strategy.
     *
     * @return HttpClientPoolItem Return a http client that can do both sync or async
     *
     * @throws HttpClientNotFoundException When no http client has been found into the pool
     */
    abstract protected function chooseHttpClient(): HttpClientPoolItem;

    /**
     * {@inheritdoc}
     */
    public function sendAsyncRequest(RequestInterface $request)
    {
        return $this->chooseHttpClient()->sendAsyncRequest($request);
    }

    /**
     * {@inheritdoc}
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return $this->chooseHttpClient()->sendRequest($request);
    }
}
