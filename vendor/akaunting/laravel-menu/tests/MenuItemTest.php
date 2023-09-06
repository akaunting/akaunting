<?php

namespace Akaunting\Menu\Tests;

use Akaunting\Menu\Menu;
use Akaunting\Menu\MenuItem;
use Illuminate\Support\Arr;

class MenuItemTest extends TestCase
{
    /**
     * @var Menu
     */
    private $menu;

    public function setUp() : void
    {
        parent::setUp();

        $this->menu = app(Menu::class);
    }

    /** @test */
    public function it_can_make_an_empty_menu_item()
    {
        $menuItem = MenuItem::make([]);

        $this->assertInstanceOf(MenuItem::class, $menuItem);
    }

    /** @test */
    public function it_can_set_properties_on_menu_item()
    {
        $properties = [
            'url' => 'my.url',
            'route' => 'my.route',
            'title' => 'My Menu item',
            'name' => 'my-menu-item',
            'icon' => 'fa fa-user',
            'parent' => 1,
            'attributes' => [],
            'active' => false,
            'order' => 1,
        ];

        $menuItem = MenuItem::make($properties);

        $this->assertEquals($properties, $menuItem->getProperties());
    }

    /** @test */
    public function it_can_fill_a_menu_item_with_allowed_properties()
    {
        $properties = [
            'url' => 'my.url',
            'route' => 'my.route',
            'title' => 'My Menu item',
            'name' => 'my-menu-item',
            'icon' => 'fa fa-user',
            'parent' => 1,
            'attributes' => [],
            'active' => false,
            'order' => 1,
        ];

        $menuItem = MenuItem::make($properties);

        $this->assertEquals('my.url', $menuItem->url);
        $this->assertEquals('my.route', $menuItem->route);
        $this->assertEquals('My Menu item', $menuItem->title);
        $this->assertEquals('my-menu-item', $menuItem->name);
        $this->assertEquals('fa fa-user', $menuItem->icon);
        $this->assertSame(1, $menuItem->parent);
        $this->assertSame([], $menuItem->attributes);
        $this->assertFalse($menuItem->active);
        $this->assertSame(1, $menuItem->order);
    }

    /** @test */
    public function it_can_set_icon_via_attributes()
    {
        $menuItem = MenuItem::make(['attributes' => ['icon' => 'fa fa-user']]);

        $this->assertEquals('fa fa-user', $menuItem->icon);
    }

    /** @test */
    public function it_can_add_a_child_menu_item()
    {
        $menuItem = MenuItem::make(['title' => 'Parent Item']);
        $menuItem->child(['title' => 'Child Item']);

        $this->assertCount(1, $menuItem->getChilds());
    }

    /** @test */
    public function it_can_get_ordered_children()
    {
        $this->app['config']->set('menu.ordering', true);
        $menuItem = MenuItem::make(['title' => 'Parent Item']);
        $menuItem->child(['title' => 'Child Item', 'order' => 10]);
        $menuItem->child(['title' => 'First Child Item', 'order' => 1]);

        $children = $menuItem->getChilds();
        $this->assertEquals('First Child Item', $children[1]->title);
        $this->assertEquals('Child Item', $children[0]->title);
    }

    /** @test */
    public function it_can_create_a_dropdown_menu_item()
    {
        $menuItem = MenuItem::make(['title' => 'Parent Item']);
        $menuItem->dropdown('Dropdown item', function (MenuItem $sub) {
            $sub->url('settings/account', 'Account');
            $sub->url('settings/password', 'Password');
        });
        $this->assertCount(1, $menuItem->getChilds());
        $this->assertCount(2, $menuItem->getChilds()[0]->getChilds());
    }

    /** @test */
    public function it_can_make_a_simple_route_menu_item()
    {
        $menuItem = MenuItem::make(['title' => 'Parent Item']);
        $menuItem->dropdown('Dropdown item', function (MenuItem $sub) {
            $sub->route('settings.account', 'Account', ['user_id' => 1]);
        });
        $children = $menuItem->getChilds()[0]->getChilds();

        $this->assertCount(1, $children);
        $childMenuItem = Arr::first($children);
        $this->assertEquals('settings.account', $childMenuItem->route[0]);
        $this->assertEquals(['user_id' => 1], $childMenuItem->route[1]);
    }

    /** @test */
    public function it_can_make_a_route_menu_item()
    {
        $menuItem = MenuItem::make(['title' => 'Parent Item']);
        $menuItem->dropdown('Dropdown item', function (MenuItem $sub) {
            $sub->route('settings.account', 'Account', ['user_id' => 1], 1, ['my-attr' => 'value']);
        });
        $children = $menuItem->getChilds()[0]->getChilds();

        $this->assertCount(1, $children);
        $childMenuItem = Arr::first($children);
        $this->assertEquals('settings.account', $childMenuItem->route[0]);
        $this->assertEquals(['user_id' => 1], $childMenuItem->route[1]);
        $this->assertSame(1, $childMenuItem->order);
        $this->assertEquals(['my-attr' => 'value'], $childMenuItem->attributes);
    }

    /** @test */
    public function it_can_make_a_simple_url_menu_item()
    {
        $menuItem = MenuItem::make(['title' => 'Parent Item']);
        $menuItem->dropdown('Dropdown item', function (MenuItem $sub) {
            $sub->url('settings/account', 'Account');
        });
        $children = $menuItem->getChilds()[0]->getChilds();

        $this->assertCount(1, $children);
        $childMenuItem = Arr::first($children);
        $this->assertEquals('settings/account', $childMenuItem->url);
        $this->assertEquals('Account', $childMenuItem->title);
    }

    /** @test */
    public function it_can_make_a_url_menu_item()
    {
        $menuItem = MenuItem::make(['title' => 'Parent Item']);
        $menuItem->dropdown('Dropdown item', function (MenuItem $sub) {
            $sub->url('settings/account', 'Account', 1, ['my-attr' => 'value']);
        });
        $children = $menuItem->getChilds()[0]->getChilds();

        $this->assertCount(1, $children);
        $childMenuItem = Arr::first($children);
        $this->assertEquals('settings/account', $childMenuItem->url);
        $this->assertEquals('Account', $childMenuItem->title);
        $this->assertSame(1, $childMenuItem->order);
        $this->assertEquals(['my-attr' => 'value'], $childMenuItem->attributes);
    }

    /** @test */
    public function it_can_add_a_menu_item_divider()
    {
        $menuItem = MenuItem::make(['title' => 'Parent Item']);
        $menuItem->dropdown('Dropdown item', function (MenuItem $sub) {
            $sub->url('settings/account', 'Account');
            $sub->divider();
        });

        $children = $menuItem->getChilds()[0]->getChilds();

        $this->assertCount(2, $children);
        $dividerMenuItem = $children[1];
        $this->assertEquals('divider', $dividerMenuItem->name);
        $this->assertTrue($dividerMenuItem->isDivider());
    }

    /** @test */
    public function it_can_add_a_header_menu_item()
    {
        $menuItem = MenuItem::make(['title' => 'Parent Item']);
        $menuItem->dropdown('Dropdown item', function (MenuItem $sub) {
            $sub->header('User Stuff');
            $sub->url('settings/account', 'Account');
        });

        $children = $menuItem->getChilds()[0]->getChilds();

        $this->assertCount(2, $children);
        $headerItem = $children[0];
        $this->assertEquals('header', $headerItem->name);
        $this->assertEquals('User Stuff', $headerItem->title);
        $this->assertTrue($headerItem->isHeader());
    }

    /** @test */
    public function it_can_get_the_correct_url_for_url_type()
    {
        $menuItem = MenuItem::make(['url' => 'settings/account', 'title' => 'Parent Item']);

        $this->assertEquals('http://localhost/settings/account', $menuItem->getUrl());
    }

    /** @test */
    public function it_can_get_the_correct_url_for_route_type()
    {
        $this->app['router']->get('settings/account', ['as' => 'settings.account']);
        $menuItem = MenuItem::make(['title' => 'Parent Item']);
        $menuItem->dropdown('Dropdown item', function (MenuItem $sub) {
            $sub->route('settings.account', 'Account');
        });
        $children = $menuItem->getChilds()[0]->getChilds();
        $childMenuItem = Arr::first($children);

        $this->assertEquals('http://localhost/settings/account', $childMenuItem->getUrl());
    }

    /** @test */
    public function it_can_get_request_uri()
    {
        $menuItem = MenuItem::make(['url' => 'settings/account', 'title' => 'Parent Item']);

        $this->assertEquals('settings/account', $menuItem->getRequest());
    }

    /** @test */
    public function it_can_get_the_icon_html_attribute()
    {
        $menuItem = MenuItem::make(['url' => 'settings/account', 'title' => 'Parent Item', 'icon' => 'fa fa-user']);

        $this->assertEquals('<i class="fa fa-user"></i>', $menuItem->getIcon());
    }

    /** @test */
    public function it_returns_no_icon_if_none_exist()
    {
        $menuItem = MenuItem::make(['url' => 'settings/account', 'title' => 'Parent Item']);

        $this->assertNull($menuItem->getIcon());
    }

    /** @test */
    public function it_returns_default_icon_if_none_exist()
    {
        $menuItem = MenuItem::make(['url' => 'settings/account', 'title' => 'Parent Item']);

        $this->assertEquals('<i class="fa fa-user"></i>', $menuItem->getIcon('fa fa-user'));
    }

    /** @test */
    public function it_can_get_item_properties()
    {
        $menuItem = MenuItem::make(['url' => 'settings/account', 'title' => 'Parent Item']);

        $this->assertEquals(['url' => 'settings/account', 'title' => 'Parent Item'], $menuItem->getProperties());
    }

    /** @test */
    public function it_can_get_item_html_attributes()
    {
        $menuItem = MenuItem::make(['url' => 'settings/account', 'title' => 'Parent Item', 'attributes' => ['my-attr' => 'value']]);

        $this->assertEquals(' my-attr="value"', $menuItem->getAttributes());
    }

    /** @test */
    public function it_can_check_for_a_submenu()
    {
        $menuItem = MenuItem::make(['title' => 'Parent Item']);
        $menuItem->dropdown('Dropdown item', function (MenuItem $sub) {
            $sub->header('User Stuff');
            $sub->url('settings/account', 'Account');
        });

        $this->assertTrue($menuItem->hasSubMenu());
        $this->assertTrue($menuItem->hasChilds());
    }

    public function it_can_check_active_state_on_item()
    {
    }
}
