<?php

namespace App\Events\Module;

use Illuminate\Queue\SerializesModels;

class SettingShowing
{
    use SerializesModels;

    public $modules;

    /**
     * Create a new event instance.
     *
     * @param $modules
     */
    public function __construct($modules)
    {
        $this->modules = $modules;
    }
}
