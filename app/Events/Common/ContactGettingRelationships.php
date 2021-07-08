<?php

namespace App\Events\Common;

use App\Abstracts\Event;

class ContactGettingRelationships extends Event
{
    public $contact;

    public function __construct($contact)
    {
        $this->contact = $contact;
    }
}
