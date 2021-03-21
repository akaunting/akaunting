<?php

namespace Enlightn\Enlightn\Analyzers\Performance;

use Illuminate\Contracts\Config\Repository as ConfigRepository;

class HorizonSuggestionAnalyzer extends PerformanceAnalyzer
{
    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = 'Your application uses Horizon when using the Redis queue driver.';

    /**
     * The severity of the analyzer.
     *
     * @var string|null
     */
    public $severity = self::SEVERITY_MINOR;

    /**
     * The time to fix in minutes.
     *
     * @var int|null
     */
    public $timeToFix = 15;

    /**
     * Get the error message describing the analyzer insights.
     *
     * @return string
     */
    public function errorMessage()
    {
        return "Your application uses the Redis queue driver but does not use Laravel's first party package "
            ."called Horizon for queue management. Horizon not only offers a beautiful dashboard for queues "
            ."and job monitoring, it also offers configurable provisioning plans for queue workers, load "
            ."balancing strategies and memory management features. It is definitely recommended to install "
            ."the Horizon package for any Laravel application that uses Redis queues.";
    }

    /**
     * Execute the analyzer.
     *
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @return void
     */
    public function handle(ConfigRepository $config)
    {
        if (! class_exists(\Laravel\Horizon\Horizon::class)) {
            $this->markFailed();
        }
    }

    /**
     * Determine whether to skip the analyzer.
     *
     * @return bool
     */
    public function skip()
    {
        // Skip the analyzer if application does not use the Redis queue driver.
        return config('queue.connections.'.config('queue.default').'.driver') !== 'redis';
    }
}
