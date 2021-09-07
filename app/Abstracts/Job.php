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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

abstract class Job
{
    use Jobs, Relationships, Sources, Uploads;

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
        if ($request instanceof Request) {
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
        if ($request instanceof Request) {
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

    public function getRequestInstance($request)
    {
        if (!is_array($request)) {
            return $request;
        }

        $class = new class() extends FormRequest {};

        return $class->merge($request);
    }

    public function setOwner(): void
    {
        if (! $this->request instanceof Request) {
            return;
        }

        if ($this->request->has('created_by')) {
            return;
        }

        $this->request->merge(['created_by' => user_id()]);
    }

    public function setSource(): void
    {
        if (! $this->request instanceof Request) {
            return;
        }

        if ($this->request->has('created_from')) {
            return;
        }

        $this->request->merge(['created_from' => $this->getSourceName($this->request)]);
    }
}
