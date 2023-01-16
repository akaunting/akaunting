<?php

namespace App\Events\Common;

use App\Abstracts\Event;

class CompanyCreated extends Event
{
    public $company;

    public $request;

    /**
     * Create a new event instance.
     *
     * @param $company
     */
    public function __construct($company, $request = null)
    {
        $this->company = $company;
        $this->request = $request;
    }
}
