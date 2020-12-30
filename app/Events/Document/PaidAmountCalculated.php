<?php

namespace App\Events\Document;

use App\Abstracts\Event;

class PaidAmountCalculated extends Event
{
    public $model;

    /**
     * Create a new event instance.
     *
     * @param $model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }
}
