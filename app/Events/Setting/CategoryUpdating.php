<?php

namespace App\Events\Setting;

use App\Abstracts\Event;

class CategoryUpdating extends Event
{
    public $category;

    public $request;

    /**
     * Create a new event instance.
     *
     * @param $category
     * @param $request
     */
    public function __construct($category, $request)
    {
        $this->category = $category;
        $this->request  = $request;
    }
}
