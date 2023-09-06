<?php

namespace Akaunting\Menu\Presenters\Bootstrap3;

class Nav extends Navbar
{
    /**
     * {@inheritdoc }.
     */
    public function getOpenTagWrapper()
    {
        return PHP_EOL . '<ul class="nav navmenu-nav">' . PHP_EOL;
    }

    /**
     * {@inheritdoc }.
     */
    public function getMenuWithDropDownWrapper($item)
    {
        return '<li class="dropdown' . $this->getActiveStateOnChild($item, ' active open') . '">
		          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
					' . $item->getIcon() . ' ' . $item->title . '
			      	<b class="caret pull-right"></b>
			      </a>
			      <ul class="dropdown-menu navmenu-nav">
			      	' . $this->getChildMenuItems($item) . '
			      </ul>
		      	</li>'
        . PHP_EOL;
    }

    /**
     * Get multilevel menu wrapper.
     *
     * @param \Akaunting\Menu\MenuItem $item
     *
     * @return string`
     */
    public function getMultiLevelDropdownWrapper($item)
    {
        return '<li class="dropdown' . $this->getActiveStateOnChild($item, ' active open') . '">
		          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
					' . $item->getIcon() . ' ' . $item->title . '
			      	<b class="caret pull-right caret-right"></b>
			      </a>
			      <ul class="dropdown-menu navmenu-nav">
			      	' . $this->getChildMenuItems($item) . '
			      </ul>
		      	</li>'
        . PHP_EOL;
    }
}
