<?php

namespace App\Abstracts;

use App\Abstracts\Http\FormRequest;
use App\Traits\Jobs;
use App\Traits\Relationships;
use App\Traits\Uploads;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

abstract class JobShouldQueue implements ShouldQueue
{
    use InteractsWithQueue, Jobs, Queueable, Relationships, SerializesModels, Uploads;

    /**
     * Check if request is array and if so, convert to a request class.
     *
     * @param mixed $request
     * @return \Illuminate\Foundation\Http\FormRequest
     *
     * @deprecated Request is not serializable so can't use it with queues.
     */
    public function getRequestInstance($request)
    {
        return $this->getRequestAsCollection($request);
    }

    /**
     * Covert the request to collection.
     *
     * @param mixed $request
     * @return \Illuminate\Support\Collection
     */
    public function getRequestAsCollection($request)
    {
        if (is_array($request)) {
            $data = $request;

            $request = new class() extends FormRequest {};

            $request->merge($data);
        }

        return collect($request->all());
    }
}
