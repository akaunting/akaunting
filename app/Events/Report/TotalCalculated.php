<?php

namespace App\Events\Report;

use App\Abstracts\Event;

class TotalCalculated extends Event
{
    public $class;
    
    public $model;

    public $date_field;

    public $check_type;

    public $table;

    public $with_tax;

    /**
     * Create a new event instance.
     *
     * @param $class
     * @param $model
     * @param $date_field
     * @param $check_type
     * @param $table
     * @param $with_tax
     */
    public function __construct($class, $model, $date_field, $check_type, $table, $with_tax)
    {
        $this->class = $class;
        $this->model = $model;
        $this->date_field = $date_field;
        $this->check_type = $check_type;
        $this->table = $table;
        $this->with_tax = $with_tax;
    }
}
