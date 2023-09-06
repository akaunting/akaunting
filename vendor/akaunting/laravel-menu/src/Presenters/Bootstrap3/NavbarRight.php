<?php

namespace Akaunting\Menu\Presenters\Bootstrap3;

class NavbarRight extends Navbar
{
    /**
     * {@inheritdoc }.
     */
    public function getOpenTagWrapper()
    {
        return PHP_EOL . '<ul class="nav navbar-nav navbar-right">' . PHP_EOL;
    }

    /**
     * {@inheritdoc }.
     */
    public function getMenuWithDropDownWrapper($item)
    {
        return '<li class="dropdown pull-right">
			      <a href="#" class="dropdown-toggle" data-toggle="dropdown">
					' . $item->getIcon() . ' ' . $item->title . '
			      	<b class="caret"></b>
			      </a>
			      <ul class="dropdown-menu">
			      	' . $this->getChildMenuItems($item) . '
			      </ul>
		      	</li>'
        . PHP_EOL;
    }
}
