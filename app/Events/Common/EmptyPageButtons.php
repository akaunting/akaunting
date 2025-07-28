<?php

namespace App\Events\Common;

use App\Abstracts\Event;

class EmptyPageButtons extends Event
{
    public $buttons;

    public $group;

    public $page;

    public $permission_create;

    public $title;

    /**
     * Create a new event instance.
     *
     * @param $buttons
     * @param string|null $group
     * @param string|null $page
     * @param string|null $permission_create
     * @param string|null $title
     * @return void
     */
    public function __construct(&$buttons, $group = null, $page = null, $permission_create = null, $title = null)
    {
        $this->buttons = &$buttons;
        $this->group = $group;
        $this->page = $page;
        $this->permission_create = $permission_create;
        $this->title = $title;
    }
}
