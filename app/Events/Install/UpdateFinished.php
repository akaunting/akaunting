<?php

namespace App\Events\Install;

use Illuminate\Queue\SerializesModels;

class UpdateFinished
{
    use SerializesModels;

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
