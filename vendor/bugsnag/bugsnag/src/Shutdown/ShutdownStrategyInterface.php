<?php

namespace Bugsnag\Shutdown;

use Bugsnag\Client;

/**
 * Interface ShutdownStrategyInterface.
 *
 * The Bugsnag\Client has a "batch sending" mode that defers any flush() calls until after the user's code has
 * completed executing. It originally accomplished this via a hardcoded call to register_shutdown_function() in the
 * Client constructor. This has now been replaced by this interface. The Bugsnag\Client now delegates the shutdown
 * behaviour to an injected strategy object. This removes the hardcoded dependency on register_shutdown_function and
 * allows us to use, for example, framework-specific strategies.
 */
interface ShutdownStrategyInterface
{
    /**
     * Register the shutdown behaviour.
     *
     * This function is called by the Client and allows the strategy to initialise the shutdown behaviour. For example,
     * this might be a call to register_shutdown_function, or it might involve hooking into a framework's lifecycle
     * events.
     *
     * @param \Bugsnag\Client $client
     *
     * @return void
     */
    public function registerShutdownStrategy(Client $client);
}
