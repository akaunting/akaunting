<?php

namespace App\Events\Install;

use App\Abstracts\Event;

class UpdateFailed extends Event
{
    public $alias;

    public $old;

    public $new;

    public $step;

    public $message;

    /**
     * Create a new event instance.
     *
     * @param  $alias
     * @param  $old
     * @param  $new
     * @param  $step
     */
    public function __construct($alias, $old, $new, $step, $message = '')
    {
        $this->alias = $alias;
        $this->old = $old;
        $this->new = $new;
        $this->step = $step;
        $this->message = $message;
    }
}
