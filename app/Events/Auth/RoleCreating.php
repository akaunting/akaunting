<?php

namespace App\Events\Auth;

use Illuminate\Queue\SerializesModels;

class RoleCreating
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
