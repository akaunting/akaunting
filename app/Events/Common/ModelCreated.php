<?php

namespace App\Events\Common;

use App\Abstracts\Event;

class ModelCreated extends Event
{
    public $model;

    public $attributes;

    /**
     * Create a new event instance.
     *
     * @param $model
     * @param $attributes
     */
    public function __construct($model, $attributes)
    {
        $this->model = $model;
        $this->attributes = $attributes;
    }
}
