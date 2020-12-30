<?php

namespace App\Events\Auth;

use App\Abstracts\Event;

class Authenticated extends Event
{
    public $alias;

    public $company_id;

    public $protocol;

    /**
     * Create a new event instance.
     *
     * @param  $alias
     * @param  $company_id
     * @param  $protocol
     */
    public function __construct($alias, $company_id = null, $protocol = 'basic')
    {
        $this->alias = $alias;
        $this->company_id = $company_id;
        $this->protocol = $protocol;
    }
}
