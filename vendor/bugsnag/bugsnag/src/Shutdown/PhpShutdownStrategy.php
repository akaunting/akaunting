<?php

namespace Bugsnag\Shutdown;

use Bugsnag\Client;

/**
 * Class PhpShutdownStrategy.
 *
 * Use the built-in PHP shutdown function
 */
class PhpShutdownStrategy implements ShutdownStrategyInterface
{
    /**
     * @param Client $client
     *
     * @return void
     */
    public function registerShutdownStrategy(Client $client)
    {
        register_shutdown_function([$client, 'flush']);
    }
}
