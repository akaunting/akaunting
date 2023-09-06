<?php

use Riverskies\Laravel\MobileDetect\Directives\NotTabletBladeDirective;

class NotTabletBladeDirectiveTest extends TestCase
{
    /**
     * Set up the world.
     */
    public function setUp()
    {
        parent::setUp();

        $this->setUpTemplateEngine(new NotTabletBladeDirective);
    }

    /** @test */
    public function it_will_not_render_if_tablet()
    {
        $this->expectMobileDetectReturn(function($md) {
            $md->isTablet()->willReturn(true);
        });

        $html = $this->blade->view()->make('test')->render();

        $this->assertEquals('', $this->clean($html));
    }

    /** @test */
    public function it_will_render_if_not_tablet()
    {
        $this->expectMobileDetectReturn(function($md) {
            $md->isTablet()->willReturn(false);
        });

        $html = $this->blade->view()->make('test')->render();

        $this->assertEquals('<h1>Test</h1>', $this->clean($html));
    }

    /** @test */
    public function it_will_display_else_if_exist_and_tablet()
    {
        $this->expectMobileDetectReturn(function($md) {
            $md->isTablet()->willReturn(true);
        });

        $html = $this->blade->view()->make('test-else')->render();

        $this->assertEquals('<h1>Else</h1>', $this->clean($html));
    }
}
