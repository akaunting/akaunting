<?php

namespace Bugsnag\Callbacks;

use Bugsnag\Report;
use Exception;

class CustomUser
{
    /**
     * The user resolver.
     *
     * @var callable
     */
    protected $resolver;

    /**
     * Create a new custom user callback instance.
     *
     * @param callable $resolver the user resolver
     *
     * @return void
     */
    public function __construct(callable $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * Execute the user data callback.
     *
     * @param \Bugsnag\Report $report the bugsnag report instance
     *
     * @return void
     */
    public function __invoke(Report $report)
    {
        $resolver = $this->resolver;

        try {
            if ($user = $resolver()) {
                $report->setUser($user);
            }
        } catch (Exception $e) {
            // Ignore any errors.
        }
    }
}
