<?php

use Riverskies\Laravel\MobileDetect\Directives\TabletBladeDirective;

class TabletBladeDirectiveTest extends TestCase
{
    /**
     * Set up the world.
     */
    public function setUp()
    {
        parent::setUp();

        $this->setUpTemplateEngine(new TabletBladeDirective);
    }

    /** @test */
    public function it_will_render_if_tablet()
    {
        $this->expectMobileDetectReturn(function($md) {
            $md->isTablet()->willReturn(true);
        });

        $html = $this->blade->view()->make('test')->render();

        $this->assertEquals('<h1>Test</h1>', $this->clean($html));
    }

    /** @test */
    public function it_will_not_render_if_not_tablet()
    {
        $this->expectMobileDetectReturn(function($md) {
            $md->isTablet()->willReturn(false);
        });

        $html = $this->blade->view()->make('test')->render();

        $this->assertEquals('', $this->clean($html));
    }

    /** @test */
    public function it_will_display_else_if_exist_and_not_tablet()
    {
        $this->expectMobileDetectReturn(function($md) {
            $md->isTablet()->willReturn(false);
        });

        $html = $this->blade->view()->make('test-else')->render();

        $this->assertEquals('<h1>Else</h1>', $this->clean($html));
    }

    /** @test */
    public function it_will_still_display_tablet_if_is_tablet_and_else_exists()
    {
        $this->expectMobileDetectReturn(function($md) {
            $md->isTablet()->willReturn(true);
        });

        $html = $this->blade->view()->make('test-else')->render();

        $this->assertEquals('<h1>Test</h1>', $this->clean($html));
    }
}
