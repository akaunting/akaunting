<?php

namespace App\Events\Install;

use App\Abstracts\Event;

class UpdateCacheCleared extends Event
{
    public $company_id;

    /**
     * Create a new event instance.
     *
     * @param  $company_id
     */
    public function __construct($company_id)
    {
        $this->company_id = $company_id;
    }
}
