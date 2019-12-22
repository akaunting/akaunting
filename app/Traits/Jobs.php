<?php

namespace App\Traits;

trait Jobs
{
    /**
     * Dispatch a job to its appropriate handler.
     *
     * @param mixed $job
     * @return mixed
     */
    public function dispatch($job)
    {
        $function = $this->getDispatchFunction();

        return $function($job);
    }

    /**
     * Dispatch a command to its appropriate handler in the current process.
     *
     * @param mixed $job
     * @param mixed $handler
     * @return mixed
     */
    public function dispatchNow($job, $handler = null)
    {
        $result = dispatch_now($job, $handler);

        return $result;
    }

    public function getDispatchFunction()
    {
        $config = config('queue.default');

        return ($config == 'sync') ? 'dispatch_now' : 'dispatch';
    }
}
