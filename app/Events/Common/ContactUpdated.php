<?php

namespace App\Events\Common;

use App\Abstracts\Event;
use App\Models\Common\Contact;

class ContactUpdated extends Event
{
    public $contact;

    public $request;

    /**
     * Create a new event instance.
     *
     * @param $contact
     * @param $request
     */
    public function __construct(Contact $contact, $request)
    {
        $this->contact = $contact;
        $this->request = $request;
    }
}
