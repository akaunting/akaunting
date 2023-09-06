<?php

declare(strict_types=1);

namespace Http\Client\Common;

use Http\Client\Exception as HttplugException;
use Http\Client\HttpAsyncClient;
use Http\Client\HttpClient;
use Http\Client\Promise\HttpFulfilledPromise;
use Http\Client\Promise\HttpRejectedPromise;
use Http\Promise\Promise;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * The client managing plugins and providing a decorator around HTTP Clients.
 *
 * @author Joel Wurtz <joel.wurtz@gmail.com>
 */
final class PluginClient implements HttpClient, HttpAsyncClient
{
    /**
     * An HTTP async client.
     *
     * @var HttpAsyncClient
     */
    private $client;

    /**
     * The plugin chain.
     *
     * @var Plugin[]
     */
    private $plugins;

    /**
     * A list of options.
     *
     * @var array
     */
    private $options;

    /**
     * @param ClientInterface|HttpAsyncClient $client  An HTTP async client
     * @param Plugin[]                        $plugins A plugin chain
     * @param array{'max_restarts'?: int}     $options
     */
    public function __construct($client, array $plugins = [], array $options = [])
    {
        if ($client instanceof HttpAsyncClient) {
            $this->client = $client;
        } elseif ($client instanceof ClientInterface) {
            $this->client = new EmulatedHttpAsyncClient($client);
        } else {
            throw new \TypeError(
                sprintf('%s::__construct(): Argument #1 ($client) must be of type %s|%s, %s given', self::class, ClientInterface::class, HttpAsyncClient::class, get_debug_type($client))
            );
        }

        $this->plugins = $plugins;
        $this->options = $this->configure($options);
    }

    /**
     * {@inheritdoc}
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        // If the client doesn't support sync calls, call async
        if (!$this->client instanceof ClientInterface) {
            return $this->sendAsyncRequest($request)->wait();
        }

        // Else we want to use the synchronous call of the underlying client,
        // and not the async one in the case we have both an async and sync call
        $pluginChain = $this->createPluginChain($this->plugins, function (RequestInterface $request) {
            try {
                return new HttpFulfilledPromise($this->client->sendRequest($request));
            } catch (HttplugException $exception) {
                return new HttpRejectedPromise($exception);
            }
        });

        return $pluginChain($request)->wait();
    }

    /**
     * {@inheritdoc}
     */
    public function sendAsyncRequest(RequestInterface $request)
    {
        $pluginChain = $this->createPluginChain($this->plugins, function (RequestInterface $request) {
            return $this->client->sendAsyncRequest($request);
        });

        return $pluginChain($request);
    }

    /**
     * Configure the plugin client.
     */
    private function configure(array $options = []): array
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'max_restarts' => 10,
        ]);

        $resolver->setAllowedTypes('max_restarts', 'int');

        return $resolver->resolve($options);
    }

    /**
     * Create the plugin chain.
     *
     * @param Plugin[] $plugins        A plugin chain
     * @param callable $clientCallable Callable making the HTTP call
     *
     * @return callable(RequestInterface): Promise
     */
    private function createPluginChain(array $plugins, callable $clientCallable): callable
    {
        return new PluginChain($plugins, $clientCallable, $this->options);
    }
}
