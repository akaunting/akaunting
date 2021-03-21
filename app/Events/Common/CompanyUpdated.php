<?php

namespace App\Events\Common;

use App\Abstracts\Event;

class CompanyUpdated extends Event
{
    public $company;

    public $request;

    /**
     * Create a new event instance.
     *
     * @param $company
     * @param $request
     */
    public function __construct($company, $request)
    {
        $this->company = $company;
        $this->request = $request;
    }
}
