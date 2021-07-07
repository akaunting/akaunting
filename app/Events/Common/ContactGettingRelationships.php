<?php

namespace App\Events\Common;

use App\Abstracts\Event;

class ContactGettingRelationships extends Event
{
    public $rel;

    public function __construct($rel)
    {
        $this->rel = $rel;
    }
}
