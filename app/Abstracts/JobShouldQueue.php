<?php

namespace App\Abstracts;

use App\Abstracts\Http\FormRequest;
use App\Interfaces\Job\HasOwner;
use App\Interfaces\Job\HasSource;
use App\Interfaces\Job\ShouldCreate;
use App\Interfaces\Job\ShouldDelete;
use App\Interfaces\Job\ShouldUpdate;
use App\Traits\Jobs;
use App\Traits\Relationships;
use App\Traits\Sources;
use App\Traits\Uploads;
use App\Utilities\QueueCollection;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

abstract class JobShouldQueue implements ShouldQueue
{
    use InteractsWithQueue, Jobs, Queueable, Relationships, SerializesModels, Sources, Uploads;

    protected $model;

    protected $request;

    public function __construct(...$arguments)
    {
        $this->booting(...$arguments);
        $this->bootCreate(...$arguments);
        $this->bootUpdate(...$arguments);
        $this->bootDelete(...$arguments);
        $this->booted(...$arguments);
    }

    public function booting(...$arguments): void
    {
        //
    }

    public function bootCreate(...$arguments): void
    {
        if (! $this instanceof ShouldCreate) {
            return;
        }

        $request = $this->getRequestInstance($arguments[0]);
        if ($request instanceof QueueCollection) {
            $this->request = $request;
        }

        if ($this instanceof HasOwner) {
            $this->setOwner();
        }

        if ($this instanceof HasSource) {
            $this->setSource();
        }
    }

    public function bootUpdate(...$arguments): void
    {
        if (! $this instanceof ShouldUpdate) {
            return;
        }

        if ($arguments[0] instanceof Model) {
            $this->model = $arguments[0];
        }

        $request = $this->getRequestInstance($arguments[1]);
        if ($request instanceof QueueCollection) {
            $this->request = $request;
        }
    }

    public function bootDelete(...$arguments): void
    {
        if (! $this instanceof ShouldDelete) {
            return;
        }

        if ($arguments[0] instanceof Model) {
            $this->model = $arguments[0];
        }
    }

    public function booted(...$arguments): void
    {
        //
    }

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
     * @return \App\Utilities\QueueCollection
     */
    public function getRequestAsCollection($request)
    {
        if (is_array($request)) {
            $data = $request;

            $request = new class() extends FormRequest {};

            $request->merge($data);
        }

        return new QueueCollection($request->all());
    }

    public function setOwner(): void
    {
        if (! $this->request instanceof QueueCollection) {
            return;
        }

        if ($this->request->has('created_by')) {
            return;
        }

        $this->request->merge(['created_by' => user_id()]);
    }

    public function setSource(): void
    {
        if (! $this->request instanceof QueueCollection) {
            return;
        }

        if ($this->request->has('created_from')) {
            return;
        }

        $this->request->merge(['created_from' => $this->getSourceName($this->request)]);
    }
}
