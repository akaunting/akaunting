<?php

namespace App\Events\Common;

use Illuminate\Queue\SerializesModels;

class GlobalSearched
{
    use SerializesModels;

    public $search;

    /**
     * Create a new event instance.
     *
     * @param $search
     */
    public function __construct($search)
    {
        $this->search = $search;
    }
}
