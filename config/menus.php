<?php

return [

    'styles' => [
        'navbar' => \Nwidart\Menus\Presenters\Bootstrap\NavbarPresenter::class,
        'navbar-right' => \Nwidart\Menus\Presenters\Bootstrap\NavbarRightPresenter::class,
        'nav-pills' => \Nwidart\Menus\Presenters\Bootstrap\NavPillsPresenter::class,
        'nav-tab' => \Nwidart\Menus\Presenters\Bootstrap\NavTabPresenter::class,
        'sidebar' => \Nwidart\Menus\Presenters\Bootstrap\SidebarMenuPresenter::class,
        'navmenu' => \Nwidart\Menus\Presenters\Bootstrap\NavMenuPresenter::class,
        'adminlte' => \Nwidart\Menus\Presenters\Admin\AdminltePresenter::class,
        'zurbmenu' => \Nwidart\Menus\Presenters\Foundation\ZurbMenuPresenter::class,
    ],

    'ordering' => true,

];
