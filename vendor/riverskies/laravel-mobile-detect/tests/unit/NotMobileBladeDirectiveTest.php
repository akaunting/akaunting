<?php

use Riverskies\Laravel\MobileDetect\Directives\NotMobileBladeDirective;

class NotMobileBladeDirectiveTest extends TestCase
{
    /**
     * Set up the world.
     */
    public function setUp()
    {
        parent::setUp();

        $this->setUpTemplateEngine(new NotMobileBladeDirective);
    }

    /** @test */
    public function it_will_not_render_if_mobile()
    {
        $this->expectMobileDetectReturn(function($md) {
            $md->isMobile()->willReturn(true);
            $md->isTablet()->willReturn(false);
        });

        $html = $this->blade->view()->make('test')->render();

        $this->assertEquals('', $this->clean($html));
    }

    /** @test */
    public function it_will_render_if_tablet()
    {
        $this->expectMobileDetectReturn(function($md) {
            $md->isMobile()->willReturn(true);
            $md->isTablet()->willReturn(true);
        });

        $html = $this->blade->view()->make('test')->render();

        $this->assertEquals('<h1>Test</h1>', $this->clean($html));
    }

    /** @test */
    public function it_will_render_if_desktop()
    {
        $this->expectMobileDetectReturn(function($md) {
            $md->isMobile()->willReturn(false);
            $md->isTablet()->willReturn(false);
        });

        $html = $this->blade->view()->make('test')->render();

        $this->assertEquals('<h1>Test</h1>', $this->clean($html));
    }

    /** @test */
    public function it_will_display_else_if_exist_and_mobile()
    {
        $this->expectMobileDetectReturn(function($md) {
            $md->isMobile()->willReturn(true);
            $md->isTablet()->willReturn(false);
        });

        $html = $this->blade->view()->make('test-else')->render();

        $this->assertEquals('<h1>Else</h1>', $this->clean($html));
    }
}
