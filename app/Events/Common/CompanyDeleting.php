<?php

namespace App\Events\Common;

use App\Abstracts\Event;

class CompanyDeleting extends Event
{
    public $company;

    public $current_company_id;

    /**
     * Create a new event instance.
     *
     * @param $company
     * @param $current_company_id
     */
    public function __construct($company, $current_company_id)
    {
        $this->company = $company;
        $this->current_company_id = $current_company_id;
    }
}
