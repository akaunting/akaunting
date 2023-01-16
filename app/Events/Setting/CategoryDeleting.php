<?php

namespace App\Events\Setting;

use App\Abstracts\Event;

class CategoryDeleting extends Event
{
    public $category;

    /**
     * Create a new event instance.
     *
     * @param $category
     */
    public function __construct($category)
    {
        $this->category = $category;
    }
}
