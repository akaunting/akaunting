<?php

declare(strict_types=1);

namespace Http\Client\Common;

use Http\Client\HttpAsyncClient;
use Psr\Http\Client\ClientInterface;

/**
 * Factory to create PluginClient instances. Using this factory instead of calling PluginClient constructor will enable
 * the Symfony profiling without any configuration.
 *
 * @author Fabien Bourigault <bourigaultfabien@gmail.com>
 */
final class PluginClientFactory
{
    /**
     * @var (callable(ClientInterface|HttpAsyncClient, Plugin[], array): PluginClient)|null
     */
    private static $factory;

    /**
     * Set the factory to use.
     * The callable to provide must have the same arguments and return type as PluginClientFactory::createClient.
     * This is used by the HTTPlugBundle to provide a better Symfony integration.
     * Unlike the createClient method, this one is static to allow zero configuration profiling by hooking into early
     * application execution.
     *
     * @internal
     *
     * @param callable(ClientInterface|HttpAsyncClient, Plugin[], array): PluginClient $factory
     */
    public static function setFactory(callable $factory): void
    {
        static::$factory = $factory;
    }

    /**
     * @param ClientInterface|HttpAsyncClient $client
     * @param Plugin[]                        $plugins
     * @param array{'client_name'?: string}   $options
     *
     * Configuration options:
     *   - client_name: to give client a name which may be used when displaying client information
     *     like in the HTTPlugBundle profiler
     *
     * @see PluginClient constructor for PluginClient specific $options.
     */
    public function createClient($client, array $plugins = [], array $options = []): PluginClient
    {
        if (!$client instanceof ClientInterface && !$client instanceof HttpAsyncClient) {
            throw new \TypeError(
                sprintf('%s::createClient(): Argument #1 ($client) must be of type %s|%s, %s given', self::class, ClientInterface::class, HttpAsyncClient::class, get_debug_type($client))
            );
        }

        if (static::$factory) {
            $factory = static::$factory;

            return $factory($client, $plugins, $options);
        }

        unset($options['client_name']);

        return new PluginClient($client, $plugins, $options);
    }
}
