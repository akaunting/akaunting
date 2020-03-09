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

    /**
     * Dispatch a job to its appropriate handler and return a response array for ajax calls.
     *
     * @param mixed $job
     * @return mixed
     */
    public function ajaxDispatch($job)
    {
        try {
            $data = $this->dispatch($job);

            $response = [
                'success' => true,
                'error' => false,
                'data' => $data,
                'message' => '',
            ];
        } catch(\Exception $e) {
            $response = [
                'success' => false,
                'error' => true,
                'data' => null,
                'message' => $e->getMessage(),
            ];
        }

        return $response;
    }

    public function getDispatchFunction()
    {
        $config = config('queue.default');

        return ($config == 'sync') ? 'dispatch_now' : 'dispatch';
    }
}
