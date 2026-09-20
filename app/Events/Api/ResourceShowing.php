<?php

namespace App\Events\Api;

use App\Abstracts\Event;

class ResourceShowing extends Event
{
    public $model;

    public $resources = [];

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function addResource(string $key, $value): void
    {
        $this->resources[$key] = $value;
    }

}
