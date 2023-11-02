<?php

namespace App\View\Presenters;

use Akaunting\Menu\Presenters\Presenter;
use Illuminate\Support\Str;

class Menu extends Presenter
{
    /**
     * {@inheritdoc }.
     */
    public function getOpenTagWrapper()
    {
        return PHP_EOL . '<div class="navbar-inner">
            <!-- Collapse -->
            <div id="sidenav-collapse-main">
                <!-- Nav items -->
                <ul class="flex flex-col justify-center">' . PHP_EOL;
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
        return '<li class="group relative pb-2.5 text-sm">
                    <a id="' . $this->getId($item) . '" class="' . $this->getClass($item) . ''. $this->getActiveState($item) . '" href="' . $item->getUrl() . '" ' . $item->getAttributes() . '>
                        ' . $this->getIcon($item) . '
                        ' . $item->title . '
                        <span class="bg-purple absolute h-5 -right-5 rounded-tl-lg rounded-bl-lg opacity-0 group-hover:opacity-100 transition-all pointer-events-none" style="width: 5px;"></span>
                    </a>
                </li>'
                . PHP_EOL;
    }

    /**
     * {@inheritdoc }.
     */
    public function getActiveState($item, $state = ' font-semibold')
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
    public function getActiveStateOnChild($item, $state = 'open')
    {
        return $item->hasActiveOnChild() ? $state : '';
    }

    /**
     * Get active state on child items.
     *
     * @param $item
     * @param string $state
     *
     * @return null|string
     */
    public function getShowStateOnChild($item, $state = 'open')
    {
        return $item->hasActiveOnChild() ? $state : ' ';
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

        return '
        <details ' . $this->getActiveStateOnChild($item) . '>
            <summary class="block" href="#navbar-' . $id . '">
                <div class="relative pb-2.5 flex items-center cursor-pointer text-purple text-sm">
                    ' . $this->getIcon($item) . '
                    ' . $item->title . '
                    ' . $this->getChevron($item) . '
                </div>
            </summary>
            <div class="mt-2 ml-8 menu__submenu" id="navbar-' . $id . '">
                <ul class="relative pb-2.5">
                    ' . $this->getChildMenuItems($item) . '
                </ul>
            </div>
        </details>'
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

        return '<details class="relative" ' . $this->getActiveStateOnChild($item) . '>
                    <summary class="' . $this->getClass($item). '" href="#navbar-' . $id . '" aria-controls="navbar-' . $id . '">
                        <div class="pb-2.5 flex items-center cursor-pointer text-purple text-sm '. $this->getActiveState($item) .'">
                            ' . $this->getIcon($item) . '
                            ' . $item->title . '
                            <span class="bg-purple absolute h-5 -right-5 rounded-tl-lg rounded-bl-lg opacity-0 group-hover:opacity-100 transition-all pointer-events-none" style="width: 5px;"></span>
                            ' . $this->getChevron($item) . '
                        </div>
                    </summary>
                    <div class="mt-2 ml-8 menu__submenu' . $this->getShowStateOnChild($item) . '" id="navbar-' . $id . '">
                        <ul class="relative pb-2.5">
                            ' . $this->getChildMenuItems($item) . '
                        </ul>
                    </div>
                </details>'
        . PHP_EOL;
    }

    public function iconState($item, $state = '')
    {
        return $item->isActive() ? $state : '-outlined';
    }

    public function iconChildState($item, $state = '')
    {
        return $item->hasActiveOnChild() ? $state : '-outlined';
    }

    public function getId($item)
    {
        $id = Str::of($item->getUrl())
                ->replace(url('/'), '-')
                ->replace('/' . company_id(), '')
                ->replace(['/', '?', '='], '-')
                ->trim('-')
                ->squish();

        return 'menu-' . $id;
    }

    public function getClass($item)
    {
        $class = 'flex items-center text-purple';

        $attributes = $item->attributes;

        if (!empty($attributes['class'])) {
            $class .= ' ' . $attributes['class'];
        }

        return $class;
    }

    public function getIcon($item)
    {
        if (empty($item->icon)) {
            return '';
        }

        $state = empty($item->getChilds()) ? $this->iconState($item) : $this->iconChildState($item);

        if (Str::startsWith($item->icon, 'simple-icons-')) {
            $slug = Str::replace('simple-icons-', '', $item->icon);

            $path = base_path('vendor/simple-icons/simple-icons/icons/' . $slug . '.svg');

            $icon_content = file_get_contents($path);

            $style = '';
            $styles= [];

            foreach (['width', 'height', 'stroke-width'] as $css) {
                if (empty($item->attributes['simple-icons-' . $css])) {
                    continue;
                }

                $styles[] .= $css . ':' . $item->attributes['simple-icons-' . $css] . ';';
            }

            if ($styles) {
                $style = 'style="';
                $style .= implode(' ', $styles);
                $style .= '"';
            }

            $find_str = 'xmlns="http://www.w3.org/2000/svg"';

            $icon_content = Str::replace($find_str, $find_str . $style, $icon_content);
        } elseif (Str::startsWith($item->icon, 'custom-')) {
            $path = $this->getCustomIcon($item);

            $icon_content = file_get_contents($path);
        } else {
            $icon_content = '<span class="material-icons' . $state . ' text-purple text-2xl">' . $item->icon . '</span>';
        }

        return '<div class="w-8 h-8 flex items-center justify-center ltr:mr-2 rtl:ml-2 pointer-events-none">
                    ' . $icon_content . '
                </div>' . PHP_EOL;
    }

    public function getChevron($item)
    {
        $state = $this->chevronState($item);

        return '<span class="material-icons text-purple absolute ltr:-right-1.5 rtl:-left-1.5 transform transition-all">expand' . $state . '</span>' . PHP_EOL;
    }

    public function chevronState($item, $state = '_less')
    {
        return $item->hasActiveOnChild() ? $state : '_more';
    }

    protected function getCustomIcon($item)
    {
        $slug = Str::replace('custom-', '', $item->icon);

        $base_path = 'public/img/icons/';

        $module_alias = '';

        if (! empty($item->properties['route'])) {
            $route = $item->properties['route'][0];

            $module_alias = explode('.', $route)[0];
        } elseif (! empty($item->properties['url'])) {
            $url_paths = explode('/', $item->properties['url']);

            $module_alias = count($url_paths) >= 1 ? $url_paths[1] : $url_paths[0];
        } elseif (! empty($item->childs[0])) {
            $route = $item->childs[0]->route[0];

            $module_alias = explode('.', $route)[0];
        }

        if (module($module_alias) != null) {
            $base_path = 'modules/' . Str::studly($module_alias) . '/Resources/assets/img/icons/';
        }

        $path = base_path($base_path . $slug . '.svg');

        if (! file_exists($path)) {
            $path = 'public/img/akaunting-logo-purple.svg';
        }

        return $path;
    }
}
