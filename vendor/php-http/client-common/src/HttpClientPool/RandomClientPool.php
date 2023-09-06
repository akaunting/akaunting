<?php

declare(strict_types=1);

namespace Http\Client\Common\HttpClientPool;

use Http\Client\Common\Exception\HttpClientNotFoundException;

/**
 * RoundRobinClientPool will choose the next client in the pool.
 *
 * @author Joel Wurtz <joel.wurtz@gmail.com>
 */
final class RandomClientPool extends HttpClientPool
{
    /**
     * {@inheritdoc}
     */
    protected function chooseHttpClient(): HttpClientPoolItem
    {
        $clientPool = array_filter($this->clientPool, function (HttpClientPoolItem $clientPoolItem) {
            return !$clientPoolItem->isDisabled();
        });

        if (0 === count($clientPool)) {
            throw new HttpClientNotFoundException('Cannot choose a http client as there is no one present in the pool');
        }

        return $clientPool[array_rand($clientPool)];
    }
}
