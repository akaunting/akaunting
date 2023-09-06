<?php

declare(strict_types=1);

namespace Http\Client\Common;

use Http\Client\HttpAsyncClient;
use Psr\Http\Client\ClientInterface;

/**
 * Build an instance of a PluginClient with a dynamic list of plugins.
 *
 * @author Baptiste Clavié <clavie.b@gmail.com>
 */
final class PluginClientBuilder
{
    /** @var Plugin[][] List of plugins ordered by priority [priority => Plugin[]]). */
    private $plugins = [];

    /** @var array Array of options to give to the plugin client */
    private $options = [];

    /**
     * @param int $priority Priority of the plugin. The higher comes first.
     */
    public function addPlugin(Plugin $plugin, int $priority = 0): self
    {
        $this->plugins[$priority][] = $plugin;

        return $this;
    }

    /**
     * @param mixed $value
     */
    public function setOption(string $name, $value): self
    {
        $this->options[$name] = $value;

        return $this;
    }

    public function removeOption(string $name): self
    {
        unset($this->options[$name]);

        return $this;
    }

    /**
     * @param ClientInterface|HttpAsyncClient $client
     */
    public function createClient($client): PluginClient
    {
        if (!$client instanceof ClientInterface && !$client instanceof HttpAsyncClient) {
            throw new \TypeError(
                sprintf('%s::createClient(): Argument #1 ($client) must be of type %s|%s, %s given', self::class, ClientInterface::class, HttpAsyncClient::class, get_debug_type($client))
            );
        }

        $plugins = $this->plugins;

        if (0 === count($plugins)) {
            $plugins[] = [];
        }

        krsort($plugins);
        $plugins = array_merge(...$plugins);

        return new PluginClient(
            $client,
            array_values($plugins),
            $this->options
        );
    }
}
