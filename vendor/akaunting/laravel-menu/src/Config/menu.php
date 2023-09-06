<?php

return [

    'styles' => [
        // Boostrap 3
        'bs3-navbar' => \Akaunting\Menu\Presenters\Bootstrap3\Navbar::class,
        'bs3-navbar-right' => \Akaunting\Menu\Presenters\Bootstrap3\NavbarRight::class,
        'bs3-nav-pills' => \Akaunting\Menu\Presenters\Bootstrap3\NavPills::class,
        'bs3-nav-tab' => \Akaunting\Menu\Presenters\Bootstrap3\NavTab::class,
        'bs3-sidebar' => \Akaunting\Menu\Presenters\Bootstrap3\Sidebar::class,
        'bs3-navmenu' => \Akaunting\Menu\Presenters\Bootstrap3\Nav::class,

        // Zurb
        'zurb' => \Akaunting\Menu\Presenters\Foundation\Zurb::class,

        // Admin
        'adminlte' => \Akaunting\Menu\Presenters\Admin\Adminlte::class,
        'argon' => \Akaunting\Menu\Presenters\Admin\Argon::class,
        'metronic-horizontal' => \Akaunting\Menu\Presenters\Admin\MetronicHorizontal::class,
        'tailwind' => \Akaunting\Menu\Presenters\Admin\Tailwind::class,
    ],

    'home_urls' => [
        '/',
    ],

    'ordering' => false,

];
