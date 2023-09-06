<?php

namespace Bugsnag\BugsnagLaravel;

class OomBootstrapper
{
    /**
     * A bit of reserved memory to ensure we are able to increase the memory
     * limit on an OOM.
     *
     * We can't reserve all of the memory that we need to send OOM reports
     * because this would have a big overhead on every request, instead of just
     * on shutdown in requests with errors.
     *
     * @var string|null
     */
    private $reservedMemory;

    /**
     * A regex that matches PHP OOM errors.
     *
     * @var string
     */
    private $oomRegex = '/^Allowed memory size of (\d+) bytes exhausted \(tried to allocate \d+ bytes\)/';

    /**
     * Allow Bugsnag to handle OOMs by registering a shutdown function that
     * increases the memory limit. This must happen before Laravel's shutdown
     * function is registered or it will have no effect.
     *
     * @return void
     */
    public function bootstrap()
    {
        $this->reservedMemory = str_repeat(' ', 1024 * 256);

        register_shutdown_function(function () {
            $this->reservedMemory = null;

            $lastError = error_get_last();

            if (!$lastError) {
                return;
            }

            $isOom = preg_match($this->oomRegex, $lastError['message'], $matches) === 1;

            if (!$isOom) {
                return;
            }

            /** @var \Bugsnag\Client|null $client */
            $client = app('bugsnag');

            // If the client exists and memory increase is enabled, bump the
            // memory limit so we can report it. The client can be missing when
            // the container isn't complete, e.g. when unit tests are running
            if ($client && $client->getMemoryLimitIncrease() !== null) {
                $currentMemoryLimit = (int) $matches[1];

                ini_set('memory_limit', $currentMemoryLimit + $client->getMemoryLimitIncrease());
            }
        });
    }
}
