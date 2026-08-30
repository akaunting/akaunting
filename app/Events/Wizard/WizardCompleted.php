<?php

namespace App\Events\Wizard;

use App\Abstracts\Event;

class WizardCompleted extends Event
{
    public $company;

    public $user;

    /**
     * Create a new event instance.
     *
     * @param $company
     * @param $user
     */
    public function __construct($company, $user = null)
    {
        $this->company = $company;
        $this->user = $user;
    }
}
