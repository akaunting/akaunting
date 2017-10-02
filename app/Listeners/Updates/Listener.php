<?php

namespace App\Listeners\Updates;

class Listener
{
    const ALIAS = '';

    const VERSION = '';

    /**
     * Check if should listen.
     *
     * @param  $event
     * @return boolean
     */
    protected function check($event)
    {
        // Apply only to the specified alias
        if ($event->alias != self::ALIAS) {
            return false;
        }

        // Do not apply to the same or newer versions
        if ($event->old >= self::VERSION) {
            return false;
        }

        return true;
    }
}
