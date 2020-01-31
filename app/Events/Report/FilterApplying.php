<?php

namespace App\Events\Report;

use Illuminate\Queue\SerializesModels;

class FilterApplying
{
    use SerializesModels;

    public $class;

    public $model;

    public $args;

    /**
     * Create a new event instance.
     *
     * @param $class
     * @param $model
     * @param $args
     */
    public function __construct($class, $model, $args)
    {
        $this->class = $class;
        $this->model = $model;
        $this->args = $args;
    }
}
