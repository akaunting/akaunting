<?php

namespace App\Events\Common;

use Illuminate\Queue\SerializesModels;

class CompanyCreating
{
    use SerializesModels;

    public $request;

    /**
     * Create a new event instance.
     *
     * @param $request
     */
    public function __construct($request)
    {
        $this->request = $request;
    }
}
