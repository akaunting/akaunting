<?php

namespace App\Abstracts\Listeners;

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

        // Do not apply to the same or newer versions
        if (version_compare($event->old, static::VERSION, '>=')) {
            return true;
        }

        return false;
    }

    /**
     * @deprecated since 2.0
     */
    public function check($event)
    {
        return !$this->skipThisUpdate($event);
    }
}
