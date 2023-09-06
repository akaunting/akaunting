<?php

namespace Akaunting\Menu\Tests;

use Akaunting\Menu\MenuBuilder;
use Akaunting\Menu\MenuItem;
use Illuminate\Config\Repository;

class MenuBuilderTest extends TestCase
{
    /** @test */
    public function it_makes_a_menu_item()
    {
        $builder = new MenuBuilder('main', app(Repository::class));

        self::assertInstanceOf(MenuItem::class, $builder->url('hello', 'world'));
    }
}
