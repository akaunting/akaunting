<?php

declare(strict_types=1);

namespace Http\Client\Common;

use Http\Client\Common\Exception\LoopException;
use Http\Promise\Promise;
use Psr\Http\Message\RequestInterface;

final class PluginChain
{
    /** @var Plugin[] */
    private $plugins;

    /** @var callable(RequestInterface): Promise */
    private $clientCallable;

    /** @var int */
    private $maxRestarts;

    /** @var int */
    private $restarts = 0;

    /**
     * @param Plugin[]                            $plugins        A plugin chain
     * @param callable(RequestInterface): Promise $clientCallable Callable making the HTTP call
     * @param array{'max_restarts'?: int}         $options
     */
    public function __construct(array $plugins, callable $clientCallable, array $options = [])
    {
        $this->plugins = $plugins;
        $this->clientCallable = $clientCallable;
        $this->maxRestarts = (int) ($options['max_restarts'] ?? 0);
    }

    private function createChain(): callable
    {
        $lastCallable = $this->clientCallable;
        $reversedPlugins = \array_reverse($this->plugins);

        foreach ($reversedPlugins as $plugin) {
            $lastCallable = function (RequestInterface $request) use ($plugin, $lastCallable) {
                return $plugin->handleRequest($request, $lastCallable, $this);
            };
        }

        return $lastCallable;
    }

    public function __invoke(RequestInterface $request): Promise
    {
        if ($this->restarts > $this->maxRestarts) {
            throw new LoopException('Too many restarts in plugin client', $request);
        }

        ++$this->restarts;

        return $this->createChain()($request);
    }
}
