<?php

namespace App\Events\Install;

use App\Abstracts\Event;

class UpdateFinished extends Event
{
    public $alias;

    public $new;

    public $old;

    /**
     * Create a new event instance.
     *
     * @param  $alias
     * @param  $old
     * @param  $new
     */
    public function __construct($alias, $new, $old)
    {
        $this->alias = $alias;
        $this->new = $new;
        $this->old = $old;
    }
}
