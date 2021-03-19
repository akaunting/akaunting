<?php

namespace App\Events\Module;

use App\Abstracts\Event;

class Installing extends Event
{
    public $alias;

    public $company_id;

    /**
     * Create a new event instance.
     *
     * @param  $alias
     * @param  $company_id
     */
    public function __construct($alias, $company_id)
    {
        $this->alias = $alias;
        $this->company_id = $company_id;
    }
}
