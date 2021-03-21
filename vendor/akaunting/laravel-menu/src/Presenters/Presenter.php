<?php

namespace Akaunting\Menu\Presenters;

use Akaunting\Menu\MenuItem;

abstract class Presenter implements PresenterInterface
{
    /**
     * Get open tag wrapper.
     *
     * @return string
     */
    public function getOpenTagWrapper()
    {
    }

    /**
     * Get close tag wrapper.
     *
     * @return string
     */
    public function getCloseTagWrapper()
    {
    }

    /**
     * Get menu tag without dropdown wrapper.
     *
     * @param \Akaunting\Menu\MenuItem $item
     *
     * @return string
     */
    public function getMenuWithoutDropdownWrapper($item)
    {
    }

    /**
     * Get divider tag wrapper.
     *
     * @return string
     */
    public function getDividerWrapper()
    {
    }

    /**
     * Get header dropdown tag wrapper.
     *
     * @param \Akaunting\Menu\MenuItem $item
     *
     * @return string
     */
    public function getHeaderWrapper($item)
    {
    }

    /**
     * Get menu tag with dropdown wrapper.
     *
     * @param \Akaunting\Menu\MenuItem $item
     *
     * @return string
     */
    public function getMenuWithDropDownWrapper($item)
    {
    }

    /**
     * Get multi level dropdown menu wrapper.
     *
     * @param \Akaunting\Menu\MenuItem $item
     *
     * @return string
     */
    public function getMultiLevelDropdownWrapper($item)
    {
    }

    /**
     * Get child menu items.
     *
     * @param \Akaunting\Menu\MenuItem $item
     *
     * @return string
     */
    public function getChildMenuItems(MenuItem $item)
    {
        $results = '';
        foreach ($item->getChilds() as $child) {
            if ($child->hidden()) {
                continue;
            }

            if ($child->hasSubMenu()) {
                $results .= $this->getMultiLevelDropdownWrapper($child);
            } elseif ($child->isHeader()) {
                $results .= $this->getHeaderWrapper($child);
            } elseif ($child->isDivider()) {
                $results .= $this->getDividerWrapper();
            } else {
                $results .= $this->getMenuWithoutDropdownWrapper($child);
            }
        }

        return $results;
    }
}
