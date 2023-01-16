<?php

namespace App\Events\Common;

use App\Abstracts\Event;

class CompanyForgettingCurrent extends Event
{
    public $company;

    /**
     * Create a new event instance.
     *
     * @param $company
     */
    public function __construct($company)
    {
        $this->company = $company;
    }
}
