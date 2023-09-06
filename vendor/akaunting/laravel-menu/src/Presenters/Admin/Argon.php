<?php

namespace Akaunting\Menu\Presenters\Admin;

use Akaunting\Menu\Presenters\Presenter;
use Illuminate\Support\Str;

class Argon extends Presenter
{
    /**
     * {@inheritdoc }.
     */
    public function getOpenTagWrapper()
    {
        return PHP_EOL . '<div class="navbar-inner">
            <!-- Collapse -->
            <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                <!-- Nav items -->
                <ul class="navbar-nav">' . PHP_EOL;
    }

    /**
     * {@inheritdoc }.
     */
    public function getCloseTagWrapper()
    {
        return PHP_EOL . '</ul>
            </div>
        </div>' . PHP_EOL;
    }

    /**
     * {@inheritdoc }.
     */
    public function getMenuWithoutDropdownWrapper($item)
    {
        $html = '<li class="nav-item">';
        $html .= '  <a class="nav-link' . $this->getActiveState($item) . '" href="' . $item->getUrl() . '" ' . $item->getAttributes() . '>';
        $html .=      $item->getIcon();
        $html .= '    <span class="nav-link-text">' . $item->title . '</span>';
        $html .= '  </a>';
        $html .= '</li>' . PHP_EOL;

        return $html;
    }

    /**
     * {@inheritdoc }.
     */
    public function getActiveState($item, $state = ' active')
    {
        return $item->isActive() ? $state : '';
    }

    /**
     * Get active state on child items.
     *
     * @param $item
     * @param string $state
     *
     * @return null|string
     */
    public function getActiveStateOnChild($item, $state = ' active show')
    {
        return $item->hasActiveOnChild() ? $state : null;
    }

    /**
     * Get active state on child items.
     *
     * @param $item
     * @param string $state
     *
     * @return null|string
     */
    public function getShowStateOnChild($item, $state = ' show')
    {
        return $item->hasActiveOnChild() ? $state : null;
    }

    /**
     * {@inheritdoc }.
     */
    public function getDividerWrapper()
    {
        return '<hr class="my-3">';
    }

    /**
     * {@inheritdoc }.
     */
    public function getHeaderWrapper($item)
    {
        return '<h6 class="navbar-heading p-0 text-muted">' . $item->title . '</h6>';
    }

    /**
     * {@inheritdoc }.
     */
    public function getMenuWithDropDownWrapper($item)
    {
        $id = Str::slug($item->title);

        return '<li class="nav-item">
    <a class="nav-link' . $this->getActiveStateOnChild($item) . '" href="#navbar-' . $id . '" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-' . $id . '">
        ' . $item->getIcon() . '
        <span class="nav-link-text">' . $item->title . '</span>
    </a>
    <div class="collapse' . $this->getShowStateOnChild($item) . '" id="navbar-' . $id . '">
        <ul class="nav nav-sm flex-column">
            ' . $this->getChildMenuItems($item) . '
        </ul>
    </div>
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
        $id = Str::slug($item->title);

        return '<li class="nav-item">
    <a class="nav-link' . $this->getActiveState($item) . '" href="#navbar-' . $id . '" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-' . $id . '">
        ' . $item->getIcon() . '
        <span class="nav-link-text">' . $item->title . '</span>
    </a>
    <div class="collapse' . $this->getShowStateOnChild($item) . '" id="navbar-' . $id . '">
        <ul class="nav nav-sm flex-column">
            ' . $this->getChildMenuItems($item) . '
        </ul>
    </div>
</li>'
        . PHP_EOL;
    }
}
