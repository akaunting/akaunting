<?php

namespace App\Listeners\Updates;

//use App\Models\Auth\Permission;
use App\Events\UpdateFinished;

class Version104 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '1.0.4';

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(UpdateFinished $event)
    {
        // Check if should listen
        if (!$this->check($event)) {
            return;
        }

        /*Permission::create([
            'name' => 'john-doe',
            'display_name' => 'John Doe',
            'description' => 'John Doe',
        ]);*/
    }
}
