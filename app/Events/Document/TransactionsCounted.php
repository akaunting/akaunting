<?php

namespace App\Events\Document;

use Illuminate\Queue\SerializesModels;

class TransactionsCounted
{
    use SerializesModels;

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
