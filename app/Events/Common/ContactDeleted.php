<?php

namespace App\Events\Common;

use Illuminate\Queue\SerializesModels;

class ContactDeleted
{
    use SerializesModels;

    public $contact_id;

    /**
     * Create a new event instance.
     *
     * @param $contact_id
     */
    public function __construct($contact_id)
    {
        $this->contact_id = $contact_id;
    }
}
