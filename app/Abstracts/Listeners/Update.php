<?php

namespace App\Abstracts\Listeners;

use App\Utilities\Versions;

abstract class Update
{
    const ALIAS = '';

    const VERSION = '';

    /**
     * Check the fired update based on alias and version.
     *
     * @param  $event
     * @return boolean
     */
    public function skipThisUpdate($event)
    {
        // Apply only to the specified alias
        if ($event->alias != static::ALIAS) {
            return true;
        }

        return ! Versions::shouldUpdate(static::VERSION, $event->old, $event->new);
    }

    /**
     * @deprecated since 2.0
     */
    public function check($event)
    {
        return !$this->skipThisUpdate($event);
    }
}
