<?php

namespace App\Events\Install;

use Illuminate\Queue\SerializesModels;

class UpdateUnzipped
{
    use SerializesModels;

    public $alias;

    public $old;

    public $new;

    /**
     * Create a new event instance.
     *
     * @param  $alias
     * @param  $old
     * @param  $new
     */
    public function __construct($alias, $old, $new)
    {
        $this->alias = $alias;
        $this->old = $old;
        $this->new = $new;
    }
}
