<?php

namespace Akaunting\Menu\Presenters\Bootstrap3;

class NavTab extends Navbar
{
    /**
     * {@inheritdoc }.
     */
    public function getOpenTagWrapper()
    {
        return PHP_EOL . '<ul class="nav nav-tabs">' . PHP_EOL;
    }
}
