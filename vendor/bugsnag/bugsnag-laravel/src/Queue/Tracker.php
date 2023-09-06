<?php

namespace Bugsnag\BugsnagLaravel\Queue;

class Tracker
{
    /**
     * The current job information.
     *
     * @var array|null
     */
    protected $job;

    /**
     * Get the current context.
     *
     * @return string|null
     */
    public function context()
    {
        if (isset($this->job['resolved'])) {
            return $this->job['resolved'];
        }
    }

    /**
     * Get the current job information.
     *
     * @return array|null
     */
    public function get()
    {
        return $this->job;
    }

    /**
     * Set the current job information.
     *
     * @param array $job
     *
     * @return void
     */
    public function set(array $job)
    {
        $this->job = $job;
    }

    /**
     * Clear the current job information.
     *
     * @return void
     */
    public function clear()
    {
        $this->job = null;
    }
}
