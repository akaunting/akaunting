<?php

namespace App\Events\Common;

use Illuminate\Queue\SerializesModels;

class ContactUpdated
{
    use SerializesModels;

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
