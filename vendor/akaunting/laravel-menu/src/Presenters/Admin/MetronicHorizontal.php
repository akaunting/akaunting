<?php

/*
 * Horizontal Menu for Metronic by KeenThemes (https://keenthemes.com/metronic/)
 * Author: Christian David (cdavid14@github.com)
 * Description: Generate horizontal menu to metronic theme
 */

namespace Akaunting\Menu\Presenters\Admin;

use Akaunting\Menu\Presenters\Presenter;

class MetronicHorizontal extends Presenter
{

    /**
     * {@inheritdoc }
     */
    public function getOpenTagWrapper()
    {
        return PHP_EOL . '<ul class="m-menu__nav  m-menu__nav--submenu-arrow ">' . PHP_EOL;
    }

    /**
     * {@inheritdoc }
     */
    public function getCloseTagWrapper()
    {
        return PHP_EOL . '</ul>' . PHP_EOL;
    }

    /**
     * {@inheritdoc }
     */
    public function getMenuWithoutDropdownWrapper($item)
    {
        return '<li ' . $this->getActiveState($item) . '>' . $item->getIcon() . '<a href="' . $item->getUrl() . '" class="m-menu__link"><span class="m-menu__item-here"></span><span class="m-menu__link-text">' . $item->title . '</span></a></li>';
    }

    /**
     * {@inheritdoc }
     */
    public function getActiveState($item)
    {
        return \Request::is($item->getRequest()) ? ' class="m-menu__item  m-menu__item--rel active"' : 'class="m-menu__item  m-menu__item--rel"';
    }

    /**
     * {@inheritdoc }
     */
    public function getDividerWrapper()
    {
        return '';
    }

    /**
     * {@inheritdoc }
     */
    public function getMenuWithDropDownWrapper($item)
    {
        if ($item->title == '...') {
            return '<li class="m-menu__item  m-menu__item--submenu m-menu__item--rel"  data-menu-submenu-toggle="click" aria-haspopup="true">
                            <a  href="#" class="m-menu__link m-menu__toggle">
                                <span class="m-menu__item-here"></span>
                                <i class="m-menu__link-icon flaticon-more-v3"></i>
                                <span class="m-menu__link-text"></span>
                            </a>
                            <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left m-menu__submenu--pull">
                                <span class="m-menu__arrow m-menu__arrow--adjust"></span>
                                <ul class="m-menu__subnav">
                                    ' . $this->getChildMenuItems($item) . '
                                </ul>
                            </div>
                        </li>' . PHP_EOL;
        } else {
            return '<li class="m-menu__item  m-menu__item--submenu m-menu__item--rel"  data-menu-submenu-toggle="click" aria-haspopup="true">
                            <a  href="#" class="m-menu__link m-menu__toggle">
                                ' . $item->getIcon() . '
                                <span class="m-menu__link-text">
                                    ' . $item->title . '
                                </span>
                                <i class="m-menu__hor-arrow la la-angle-down"></i>
                                <i class="m-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left">
                                <span class="m-menu__arrow m-menu__arrow--adjust"></span>
                                <ul class="m-menu__subnav">
                                    ' . $this->getChildMenuItems($item) . '
                                </ul>
                            </div>
                        </li>' . PHP_EOL;
        }
    }

    public function getMultiLevelDropdownWrapper($item)
    {
        return '<li class="m-menu__item  m-menu__item--submenu"  data-menu-submenu-toggle="hover" data-redirect="true" aria-haspopup="true">
                            <a  href="#" class="m-menu__link m-menu__toggle">
                                ' . $item->getIcon() . '
                                <span class="m-menu__link-text">
                                    ' . $item->title . '
                                </span>
                                <i class="m-menu__hor-arrow la la-angle-right"></i>
                                <i class="m-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--right">
                                <span class="m-menu__arrow m-menu__arrow--adjust"></span>
                                <ul class="m-menu__subnav">
                                    ' . $this->getChildMenuItems($item) . '
                                </ul>
                            </div>
                        </li>' . PHP_EOL;
    }
}
