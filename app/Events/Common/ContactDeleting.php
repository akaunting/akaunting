<?php

namespace App\Events\Common;

use App\Abstracts\Event;

class ContactDeleting extends Event
{
    public $contact;

    /**
     * Create a new event instance.
     *
     * @param $contact
     */
    public function __construct($contact)
    {
        $this->contact = $contact;
    }
}
