<?php

namespace App\Events\Common;

use App\Abstracts\Event;
use App\Models\Common\Contact;

class ContactCreated extends Event
{
    public $contact;

    /**
     * Create a new event instance.
     *
     * @param $contact
     */
    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }
}
